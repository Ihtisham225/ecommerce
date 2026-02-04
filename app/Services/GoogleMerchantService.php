<?php

namespace App\Services;

use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product as GoogleProduct;
use Google\Service\ShoppingContent\Price;
use App\Models\Product;
use App\Models\GoogleMerchantSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoogleMerchantService
{
    private $client;
    private $service;
    private $merchantId;
    private $setting;

    public function __construct()
    {
        $this->setting = GoogleMerchantSetting::first();
        if (!$this->setting || !$this->setting->is_enabled) {
            return;
        }

        $this->merchantId = $this->setting->merchant_id;
        $this->initializeClient();
    }

    private function initializeClient()
    {
        try {
            $this->client = new Client();
            $this->client->setApplicationName(config('app.name'));
            $this->client->setClientId($this->setting->client_id);
            $this->client->setClientSecret($this->setting->client_secret);
            $this->client->addScope('https://www.googleapis.com/auth/content');
            
            if ($this->setting->access_token) {
                $this->client->setAccessToken($this->setting->access_token);
                
                if ($this->client->isAccessTokenExpired() && $this->setting->refresh_token) {
                    $this->refreshToken();
                }
            }

            $this->service = new ShoppingContent($this->client);
        } catch (\Exception $e) {
            Log::error('Google Merchant Client Initialization Error: ' . $e->getMessage());
        }
    }

    private function refreshToken()
    {
        try {
            $this->client->refreshToken($this->setting->refresh_token);
            $newToken = $this->client->getAccessToken();
            
            $this->setting->update([
                'access_token' => $newToken['access_token'],
                'token_expires_at' => now()->addSeconds($newToken['expires_in'])
            ]);
        } catch (\Exception $e) {
            Log::error('Google Merchant Token Refresh Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function isConfigured()
    {
        return $this->setting && $this->setting->isConnected;
    }

    public function syncProduct(Product $product)
    {
        // No need to check sync_to_google flag anymore
        
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Google Merchant not configured'];
        }

        try {
            $googleProduct = $this->createGoogleProductObject($product);
            
            if ($product->google_product_id) {
                // Update existing product
                $result = $this->service->products->update(
                    $this->merchantId,
                    $product->google_product_id,
                    $googleProduct
                );
                $action = 'updated';
            } else {
                // Insert new product
                $result = $this->service->products->insert(
                    $this->merchantId,
                    $googleProduct
                );
                
                $product->update(['google_product_id' => $result->getId()]);
                $action = 'created';
            }

            $product->update([
                'google_last_synced' => now(),
                'google_status' => 'active'
            ]);

            return [
                'success' => true, 
                'action' => $action,
                'google_product_id' => $product->google_product_id
            ];
        } catch (\Exception $e) {
            Log::error('Google Merchant Sync Error: ' . $e->getMessage());
            $product->update(['google_status' => 'error']);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function syncAllProducts()
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Google Merchant not configured'];
        }

        // Sync ALL products (no filter needed)
        $products = Product::all();
        $results = [
            'total' => $products->count(),
            'successful' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($products as $product) {
            $result = $this->syncProduct($product);
            
            if ($result['success']) {
                $results['successful']++;
            } else {
                $results['failed']++;
                $results['errors'][] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'error' => $result['message']
                ];
            }
            
            // Add small delay to avoid rate limiting
            usleep(100000); // 0.1 second
        }

        // Update sync stats
        $this->setting->update([
            'last_sync_at' => now(),
            'total_products_synced' => $this->setting->total_products_synced + $results['successful']
        ]);

        return [
            'success' => true,
            'results' => $results
        ];
    }

    public function deleteProduct($googleProductId)
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Google Merchant not configured'];
        }

        try {
            $this->service->products->delete($this->merchantId, $googleProductId);
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Google Merchant Delete Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function createGoogleProductObject(Product $product)
    {
        $price = new Price();
        $price->setValue($product->price);
        $price->setCurrency('USD'); // Adjust as needed

        $googleProduct = new GoogleProduct();
        $googleProduct->setOfferId('PROD_' . $product->id);
        $googleProduct->setTitle($product->name);
        $googleProduct->setDescription(strip_tags($product->description ?? ''));
        $googleProduct->setLink(route('products.show', $product->slug));
        
        if ($product->mainImage && $product->mainImage->path) {
            $googleProduct->setImageLink(Storage::url($product->mainImage->path));
        }
        
        $googleProduct->setContentLanguage('en');
        $googleProduct->setTargetCountry('US'); // Adjust as needed
        $googleProduct->setChannel('online');
        $googleProduct->setAvailability($product->in_stock ? 'in stock' : 'out of stock');
        $googleProduct->setCondition('new');
        $googleProduct->setGoogleProductCategory($this->mapCategory($product->category_id));
        $googleProduct->setPrice($price);
        $googleProduct->setBrand($product->brand?->name ?? 'Generic');
        
        if ($product->gtin) {
            $googleProduct->setGtin($product->gtin);
        }
        
        if ($product->mpn) {
            $googleProduct->setMpn($product->mpn);
        }

        return $googleProduct;
    }

    private function mapCategory($categoryId)
    {
        // Simple category mapping - expand as needed
        $categories = [
            1 => 'Apparel & Accessories > Clothing',
            2 => 'Electronics > Computers',
            3 => 'Home & Garden > Furniture',
            // Add more mappings
        ];

        return $categories[$categoryId] ?? 'Other';
    }

    public function testConnection()
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Not configured'];
        }

        try {
            // Try to get product list to test connection
            $products = $this->service->products->listProducts($this->merchantId, ['maxResults' => 1]);
            return ['success' => true, 'count' => count($products->getResources())];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAuthUrl()
    {
        if (!$this->setting || !$this->setting->client_id || !$this->setting->client_secret) {
            return null;
        }

        try {
            $client = new Client();
            $client->setClientId($this->setting->client_id);
            $client->setClientSecret($this->setting->client_secret);
            $client->addScope('https://www.googleapis.com/auth/content');
            $client->setRedirectUri(route('admin.google-merchant.callback'));
            
            return $client->createAuthUrl();
        } catch (\Exception $e) {
            Log::error('Google Merchant Auth URL Error: ' . $e->getMessage());
            return null;
        }
    }
}