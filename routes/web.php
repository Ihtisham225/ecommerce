<?php

//admin
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImportController as AdminProductImportController;
use App\Http\Controllers\Admin\ProductAttributeController as AdminProductAttributeController;
use App\Http\Controllers\Admin\ProductMediaController as AdminProductMediaController;
use App\Http\Controllers\Admin\ProductMetaController as AdminProductMetaController;
use App\Http\Controllers\Admin\ProductPricingRuleController as AdminProductPricingRuleController;
use App\Http\Controllers\Admin\ProductRelationController as AdminProductRelationController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderItemController as AdminOrderItemController;
use App\Http\Controllers\Admin\OrderPaymentController as AdminOrderPaymentController;
use App\Http\Controllers\Admin\OrderInvoiceController as AdminOrderInvoiceController;
use App\Http\Controllers\Admin\OrderCustomerController as AdminOrderCustomerController;
use App\Http\Controllers\Admin\OrderImportController as AdminOrderImportController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentController as AdminBlogCommentController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\GlobalSearchController as AdminSearchController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\ProfileController;

// Frontend
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\BrandController as FrontendBrandController;
use App\Http\Controllers\Frontend\CollectionController as FrontendCollectionController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\CartController as FrontendCartController;
use App\Http\Controllers\Frontend\CheckoutController as FrontendCheckoutController;
use App\Http\Controllers\Frontend\BlogCommentController as FrontendBlogCommentController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\GlobalSearchController as FrontendSearchController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\SubscriberController as FrontendSubscriberController;

// Customer
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ContactController as CustomerContactController;

use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendPageController::class, 'welcome'])->name('home');

// Dynamic page routes
// Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');

/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Frontend Routes Starts
|--------------------------------------------------------------------------------------------------------------------------------------------
*/

// Home
// Route::get('/', function () {
//     return view('public/welcome');
// })->name('home');


// ===============================
// Products Routes Starts
// ===============================

Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [FrontendProductController::class, 'show'])->name('products.show');
Route::get('/products/featured', [FrontendProductController::class, 'featured'])->name('products.featured');

// ===============================
// Products Routes Ends
// ===============================

// ===============================
// Category Routes Starts
// ===============================

Route::get('/categories', [FrontendCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [FrontendCategoryController::class, 'show'])->name('categories.show');

// ===============================
// Category Routes Ends
// ===============================

// ===============================
// Brand Routes Starts
// ===============================

Route::get('/brands', [FrontendBrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{slug}', [FrontendBrandController::class, 'show'])->name('brands.show');

// ===============================
// Brand Routes Ends
// ===============================

// Collection Routes
Route::get('/collections', [FrontendCollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [FrontendCollectionController::class, 'show'])->name('collections.show');


// Cart Routes
Route::get('/cart', [FrontendCartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [FrontendCartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [FrontendCartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [FrontendCartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [FrontendCartController::class, 'clear'])->name('cart.clear');


// Checkout Routes
Route::get('/checkout', [FrontendCheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [FrontendCheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/direct-purchase', [FrontendCheckoutController::class, 'directPurchase'])->name('checkout.direct-purchase');


// Policy Pages
Route::get('/terms-condition', [FrontendPageController::class, 'termsCondition'])->name('terms.conditions');
Route::get('/privacy-policy', [FrontendPageController::class, 'privacyPolicy'])->name('privacy.policy');


// Subscribers (newsletter)
Route::get('subscribe', [FrontendSubscriberController::class, 'create'])->name('subscribers.create');
Route::post('subscribe', [FrontendSubscriberController::class, 'store'])->name('subscribers.store');
Route::get('subscribe/verify/{token}', [FrontendSubscriberController::class, 'verify'])->name('subscribers.verify');
Route::get('unsubscribe/{token}', [FrontendSubscriberController::class, 'unsubscribe'])->name('subscribers.unsubscribe');

// About Pages
Route::get('/about', [FrontendPageController::class, 'about'])->name('about');

// Contact Us
Route::get('/contact-us', [FrontendContactController::class, 'index'])->name('contact.us');
Route::post('/contact-us/send-message', [FrontendContactController::class, 'sendMessage'])->name('contact.send');


// Blogs
Route::resource('blogs', FrontendBlogController::class)->only('index', 'show');

// Blog Comments
Route::prefix('blogs')->name('blog-')->group(function () {
    Route::post('{slug}/comments', [FrontendBlogCommentController::class, 'store'])->name('comments.store');
    Route::post('{slug}/comments/{comment}/reply', [FrontendBlogCommentController::class, 'storeReply'])->name('comments.reply');
    Route::get('{slug}/comments/{comment}/edit', [FrontendBlogCommentController::class, 'edit'])->name('comments.edit');
    Route::put('{slug}/comments/{comment}', [FrontendBlogCommentController::class, 'update'])->name('comments.update');
});

// Global search routes
Route::get('/global-search', [FrontendSearchController::class, 'search'])
    ->name('global-search');
Route::get('/search/all', [FrontendSearchController::class, 'fullSearch'])
    ->name('search.all');

// Language Switch
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Frontend Routes End
|--------------------------------------------------------------------------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Admin Routes (Protected by auth + roles)
|--------------------------------------------------------------------------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin|staff'])->prefix('admin')->name('admin.')->group(function () {

    // -------------------------
    // ✅ Products CRUD
    // -------------------------
    Route::get('products', [AdminProductController::class,'index'])->name('products.index');
    Route::get('products/create', [AdminProductController::class,'create'])->name('products.create');
    Route::get('products/{product}/edit', [AdminProductController::class,'edit'])->name('products.edit');
    Route::get('products/{product}/show', [AdminProductController::class,'show'])->name('products.show');
    Route::delete('products/{product}', [AdminProductController::class,'destroy'])->name('products.destroy');

    // autosave - uses POST to allow FormData
    Route::post('products/{product}/autosave', [AdminProductController::class,'autosave'])->name('products.autosave');

    // publish endpoint (save + publish)
    Route::post('products/{product}/publish', [AdminProductController::class,'publish'])->name('products.publish');

    // ✅ Bulk actions
    Route::post('products/bulk', [AdminProductController::class, 'bulk'])->name('products.bulk');

    // ✅ Toggle single field (status or featured)
    Route::post('products/{product}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');

    // Import products
    Route::post('products/import/upload', [AdminProductImportController::class, 'upload'])->name('products.import.upload');

    Route::post('products/import/process-chunk', [AdminProductImportController::class, 'processChunk'])->name('products.import.processChunk');
    Route::post('products/import/cleanup', [AdminProductImportController::class, 'cleanupImportFiles'])->name('products.import.cleanup');

    //product search ajax for order route
    Route::get('products/search', [AdminProductController::class, 'search'])->name('products.search');


    // -------------------------
    // ✅ Product Variants
    // -------------------------
    Route::get('products/{product}/variants', [AdminProductVariantController::class, 'index'])->name('products.variants.index');
    Route::post('products/{product}/variants', [AdminProductVariantController::class, 'store'])->name('products.variants.store');
    Route::put('products/{product}/variants/{variant}', [AdminProductVariantController::class, 'update'])->name('products.variants.update');
    Route::delete('products/{product}/variants/{variant}', [AdminProductVariantController::class, 'destroy'])->name('products.variants.destroy');

    // -------------------------
    // ✅ Product Attributes
    // -------------------------
    Route::get('products/{product}/attributes', [AdminProductAttributeController::class, 'index'])->name('products.attributes.index');
    Route::post('products/{product}/attributes', [AdminProductAttributeController::class, 'store'])->name('products.attributes.store');
    Route::put('products/{product}/attributes/{attribute}', [AdminProductAttributeController::class, 'update'])->name('products.attributes.update');
    Route::delete('products/{product}/attributes/{attribute}', [AdminProductAttributeController::class, 'destroy'])->name('products.attributes.destroy');

    // -------------------------
    // ✅ Product Meta
    // -------------------------
    Route::get('products/{product}/meta', [AdminProductMetaController::class, 'index'])->name('products.meta.index');
    Route::post('products/{product}/meta', [AdminProductMetaController::class, 'store'])->name('products.meta.store');
    Route::put('products/{product}/meta/{meta}', [AdminProductMetaController::class, 'update'])->name('products.meta.update');
    Route::delete('products/{product}/meta/{meta}', [AdminProductMetaController::class, 'destroy'])->name('products.meta.destroy');

    // -------------------------
    // ✅ Product Media (Documents / Images)
    // -------------------------
    Route::get('products/{product}/media', [AdminProductMediaController::class, 'index'])->name('products.media.index');
    Route::delete('documents/ajax-delete/{document}', [AdminProductMediaController::class, 'ajaxDestroy'])->name('documents.ajaxDestroy');
    Route::post('documents/ajax-upload', [AdminProductMediaController::class, 'ajaxUpload'])->name('documents.ajaxUpload');
    Route::post('documents/set-as-main/{document}', [AdminProductMediaController::class, 'setAsMain'])->name('documents.set-as-main');
    Route::post('products/{product}/attach-existing-images', [AdminProductMediaController::class, 'attachExistingImages'])->name('products.attach-existing-images');
    Route::get('documents/unattached', [AdminProductMediaController::class, 'getUnattachedImages'])->name('documents.unattached');

    // -------------------------
    // ✅ Product Pricing Rules
    // -------------------------
    Route::get('products/{product}/pricing-rules', [AdminProductPricingRuleController::class, 'index'])->name('products.pricing-rules.index');
    Route::post('products/{product}/pricing-rules', [AdminProductPricingRuleController::class, 'store'])->name('products.pricing-rules.store');
    Route::put('products/{product}/pricing-rules/{rule}', [AdminProductPricingRuleController::class, 'update'])->name('products.pricing-rules.update');
    Route::delete('products/{product}/pricing-rules/{rule}', [AdminProductPricingRuleController::class, 'destroy'])->name('products.pricing-rules.destroy');

    // -------------------------
    // ✅ Product Relations (Upsell, Cross-sell, Related)
    // -------------------------
    Route::get('products/{product}/relations', [AdminProductRelationController::class, 'index'])->name('products.relations.index');
    Route::post('products/{product}/relations', [AdminProductRelationController::class, 'store'])->name('products.relations.store');
    Route::delete('products/{product}/relations/{relation}', [AdminProductRelationController::class, 'destroy'])->name('products.relations.destroy');

    // -------------------------
    // ✅ Product categories
    // -------------------------
    Route::resource('/categories', AdminCategoryController::class);
    Route::post('/categories/quick-add', [AdminCategoryController::class, 'quickAdd'])->name('admin.categories.quick-add');

    // -------------------------
    // ✅ Product brands
    // -------------------------
    Route::resource('/brands', AdminBrandController::class);
    Route::post('/brands/quick-add', [AdminBrandController::class, 'quickAdd'])->name('admin.brands.quick-add');

    // -------------------------
    // ✅ Product collections
    // -------------------------
    Route::resource('/collections', AdminCollectionController::class);
    Route::post('/collections/quick-add', [AdminCollectionController::class, 'quickAdd'])->name('admin.collections.quick-add');

    // -------------------------
    // ✅ Orders Routes
    // -------------------------
        Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/create', [AdminOrderController::class, 'create'])->name('create');
        Route::get('/{order}/edit', [AdminOrderController::class, 'edit'])->name('edit');
        Route::get('/{order}/show', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{order}', [AdminOrderController::class, 'autoSave'])->name('update');
        Route::delete('/{order}', [AdminOrderController::class, 'destroy'])->name('destroy');
        
        // Bulk actions
        Route::post('/bulk', [AdminOrderController::class, 'bulk'])->name('bulk');
        
        // Toggle actions
        Route::post('/{order}/toggle', [AdminOrderController::class, 'toggle'])->name('toggle');

        Route::post('import/upload', [AdminOrderImportController::class, 'upload'])->name('import.upload');
        Route::post('import/process-chunk', [AdminOrderImportController::class, 'processChunk'])->name('import.processChunk');
        Route::post('import/cleanup', [AdminOrderImportController::class, 'cleanupImportFiles'])->name('import.cleanup');
    });

    // -------------------------
    // ✅ Orders Items Routes
    // -------------------------
    Route::prefix('orders/{order}/items')->group(function () {
        Route::get('/', [AdminOrderItemController::class, 'index']);
        Route::post('/', [AdminOrderItemController::class, 'store']);        // Add item
        Route::put('/{item}', [AdminOrderItemController::class, 'update']);  // Update qty or price
        Route::delete('/{item}', [AdminOrderItemController::class, 'destroy']); // Remove
    });

    // -------------------------
    // ✅ Orders Payments Routes
    // -------------------------
    Route::prefix('orders/{order}/payments')->group(function () {
        Route::get('/', [AdminOrderPaymentController::class, 'index'])->name('orders.payments.index');
        Route::post('/', [AdminOrderPaymentController::class, 'store'])->name('orders.payments.store');
        Route::delete('/{payment}', [AdminOrderPaymentController::class, 'destroy'])->name('orders.payments.destroy');
    });

    // -------------------------
    // ✅ Orders Invoices Routes
    // -------------------------
    Route::prefix('orders/{order}/invoice')->group(function () {
        Route::get('/pdf', [AdminOrderInvoiceController::class, 'pdf'])->name('orders.invoice.pdf');
        Route::get('/thermal', [AdminOrderInvoiceController::class, 'thermal'])->name('orders.invoice.thermal');
    });
    
    
    // -------------------------
    // ✅ Orders Customer Routes
    // -------------------------
    Route::prefix('orders/{order}/customer')->group(function () {
        Route::post('/select', [AdminOrderCustomerController::class, 'select']);
        Route::post('/remove', [AdminOrderCustomerController::class, 'remove']);
        Route::post('/in-store', [AdminOrderCustomerController::class, 'updateInStore']);
    });
    
    
    // -------------------------
    // ✅ Customers Routes
    // -------------------------
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('index');
        Route::get('/create', [AdminCustomerController::class, 'create'])->name('create');
        Route::get('/edit/{customer}', [AdminCustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}/autosave', [AdminCustomerController::class, 'autoSave'])->name('customers.autosave');
        Route::post('/autosave', [AdminCustomerController::class, 'storeAjax'])->name('customers.autosave-new');
        Route::get('/show/{customer}', [AdminCustomerController::class, 'show'])->name('show');
        Route::get('/search', [AdminCustomerController::class, 'search'])->name('search');
        Route::post('/', [AdminCustomerController::class, 'storeAjax'])->name('store.ajax');

        //bulk
        Route::post('/bulk', [AdminCustomerController::class, 'bulk'])->name('bulk');

        // Address management routes
        Route::post('/{customer}/addresses/{address}/default-shipping', [AdminCustomerController::class, 'setDefaultShipping'])->name('addresses.set-shipping');
        Route::post('/{customer}/addresses/{address}/default-billing', [AdminCustomerController::class, 'setDefaultBilling'])->name('addresses.set-billing');
        Route::get('/{customer}/addresses', [AdminCustomerController::class, 'getAddresses'])->name('addresses.list');
    });

    // Documents
    Route::resource('documents', AdminDocumentController::class);

    // Subscribers (only admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('subscribers', AdminSubscriberController::class)->only(['index', 'destroy']);
    });

    // Blogs
    Route::resource('blogs', AdminBlogController::class);

    // Blog Categories
    Route::resource('blog-categories', AdminBlogCategoryController::class);

    // Blog Comments
    Route::post('blog-comments/{comment}/approve', [AdminBlogCommentController::class, 'approve'])->name('blog-comments.approve');
    Route::post('blog-comments/bulk-approve', [AdminBlogCommentController::class, 'bulkApprove'])->name('blog-comments.bulk-approve');
    Route::post('blog-comments/{comment}/destroy', [AdminBlogCommentController::class, 'destroy'])->name('blog-comments.destroy');
    Route::post('blog-comments/bulk-destroy', [AdminBlogCommentController::class, 'bulkDestroy'])->name('blog-comments.bulk-destroy');
    Route::get('blog-comments/trashed', [AdminBlogCommentController::class, 'trashed'])->name('blog-comments.trashed');
    Route::post('blog-comments/{id}/restore', [AdminBlogCommentController::class, 'restore'])->name('blog-comments.restore');
    Route::delete('blog-comments/{id}/force-delete', [AdminBlogCommentController::class, 'forceDelete'])->name('blog-comments.force-delete');
    Route::get('blog-comments/stats', [AdminBlogCommentController::class, 'stats'])->name('blog-comments.stats');

    // Users (only admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', AdminUserController::class);
    });

    // Contactus
    Route::delete('/contact-inquiries/{id}', [AdminContactController::class, 'destroy'])->name('contact.inquiries.delete');
    Route::post('/contact-inquiries/{id}/reply', [AdminContactController::class, 'sendReply'])->name('contact.inquiries.reply');
    Route::patch('/contact-inquiries/{id}/status', [AdminContactController::class, 'updateStatus'])->name('contact.inquiries.status');

    // Global search routes
    Route::get('/global-search', [AdminSearchController::class, 'search'])
        ->name('global-search');
    Route::get('/search/all', [AdminSearchController::class, 'fullSearch'])
        ->name('search.all');
});

/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Admin Routes Ends
|--------------------------------------------------------------------------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Customer Routes (Protected by auth + roles)
|--------------------------------------------------------------------------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    // Add customer dashboard if needed
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');

    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
    
    // Inquires
    Route::get('/contact-inquires', [CustomerContactController::class, 'index'])->name('contact.inquiries');
});

/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Customer Routes Ends
|--------------------------------------------------------------------------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| User Profile & Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => view('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/store-settings', [ProfileController::class, 'updateStore'])->name('profile.store-settings.update');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| User Profile & Dashboard Ends
|--------------------------------------------------------------------------
*/