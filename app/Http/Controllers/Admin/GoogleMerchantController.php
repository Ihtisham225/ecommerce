<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleMerchantSetting;
use App\Services\GoogleMerchantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleMerchantController extends Controller
{
    protected $merchantService;

    public function __construct(GoogleMerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function settings()
    {
        $settings = GoogleMerchantSetting::first() ?? new GoogleMerchantSetting();
        $isConnected = $this->merchantService->isConfigured();
        
        return response()->json([
            'success' => true,
            'settings' => $settings,
            'is_connected' => $isConnected,
            'auth_url' => $this->merchantService->getAuthUrl()
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'merchant_id' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'auto_sync' => 'boolean',
        ]);

        $settings = GoogleMerchantSetting::firstOrNew();
        $settings->fill($validated);
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully',
            'auth_url' => $this->merchantService->getAuthUrl()
        ]);
    }

    public function connect(Request $request)
    {
        $authUrl = $this->merchantService->getAuthUrl();
        
        if (!$authUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Please save your credentials first'
            ]);
        }

        return response()->json([
            'success' => true,
            'auth_url' => $authUrl
        ]);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect()->route('admin.store-settings.google-merchant')
                ->with('error', 'Authorization failed');
        }

        try {
            $settings = GoogleMerchantSetting::first();
            if (!$settings) {
                throw new \Exception('Please save your credentials first');
            }

            $client = new \Google\Client();
            $client->setClientId($settings->client_id);
            $client->setClientSecret($settings->client_secret);
            $client->addScope('https://www.googleapis.com/auth/content');
            $client->setRedirectUri(route('admin.google-merchant.callback'));
            
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($accessToken['error'])) {
                throw new \Exception($accessToken['error_description'] ?? 'Authentication failed');
            }

            $settings->access_token = $accessToken['access_token'];
            $settings->refresh_token = $accessToken['refresh_token'] ?? $settings->refresh_token;
            $settings->token_expires_at = now()->addSeconds($accessToken['expires_in']);
            $settings->is_enabled = true;
            $settings->save();

            return redirect()->route('admin.store-settings.google-merchant')
                ->with('success', 'Successfully connected to Google Merchant Center');
        } catch (\Exception $e) {
            Log::error('Google Merchant Auth Error: ' . $e->getMessage());
            return redirect()->route('admin.store-settings.google-merchant')
                ->with('error', 'Connection failed: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $settings = GoogleMerchantSetting::first();
        if ($settings) {
            $settings->update([
                'access_token' => null,
                'refresh_token' => null,
                'token_expires_at' => null,
                'is_enabled' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Disconnected from Google Merchant Center'
        ]);
    }

    public function testConnection()
    {
        $result = $this->merchantService->testConnection();
        
        return response()->json($result);
    }

    public function syncAll()
    {
        $result = $this->merchantService->syncAllProducts();
        
        return response()->json($result);
    }

    public function getStats()
    {
        $settings = GoogleMerchantSetting::first();
        
        return response()->json([
            'success' => true,
            'stats' => [
                'is_connected' => $settings?->is_enabled ?? false,
                'total_products_synced' => $settings?->total_products_synced ?? 0,
                'last_sync' => $settings?->last_sync_at?->format('Y-m-d H:i:s'),
                'last_error' => $settings?->last_error
            ]
        ]);
    }
}