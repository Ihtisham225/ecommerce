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
use App\Http\Controllers\Admin\OrderCustomerController as AdminOrderCustomerController;
use App\Http\Controllers\Admin\OrderImportController as AdminOrderImportController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;


use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentController as AdminBlogCommentController;
use App\Http\Controllers\Public\BlogController as PublicBlogController;
use App\Http\Controllers\Public\BlogCommentController as PublicBlogCommentController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Admin\CountryController as AdminCountryController;
use App\Http\Controllers\Public\CourseController as PublicCourseController;
use App\Http\Controllers\Public\CourseCategoryController as PublicCourseCategoryController;
use App\Http\Controllers\Public\CourseRegistrationController as PublicCourseRegistrationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\PageController as PublicPageController;
use App\Http\Controllers\Public\GlobalSearchController as PublicSearchController;
use App\Http\Controllers\Admin\GlobalSearchController as AdminSearchController;
use App\Http\Controllers\Public\ContactController as PublicConatactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Public\CourseEvaluationController as PublicCourseEvaluationController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'welcome'])->name('home');

// Dynamic page routes
// Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');

/*
|--------------------------------------------------------------------------------------------------------------------------------------------
| Public Routes Starts
|--------------------------------------------------------------------------------------------------------------------------------------------
*/

// Home
// Route::get('/', function () {
//     return view('public/welcome');
// })->name('home');


// ===============================
// Products Routes Starts
// ===============================

Route::get('/products', [PublicCourseController::class, 'index'])->name('products.index');
Route::get('/products/featured', [PublicCourseController::class, 'featured'])->name('products.featured');

// ===============================
// Products Routes Ends
// ===============================

// ===============================
// Category Routes Starts
// ===============================

Route::get('/categoriers', [PublicCourseController::class, 'index'])->name('categories.index');

// ===============================
// Category Routes Ends
// ===============================

// ===============================
// Brand Routes Starts
// ===============================

Route::get('/brands', [PublicCourseController::class, 'index'])->name('brands.index');

// ===============================
// Brand Routes Ends
// ===============================

// ===============================
// Collection Routes Starts
// ===============================

Route::get('/collections', [PublicCourseController::class, 'index'])->name('collections.index');

// ===============================
// Collection Routes Ends
// ===============================

// ===============================
// COURSE SCHEDULE REGISTRATION
// ===============================
Route::middleware(['auth'])->group(function () {
    Route::get('course-schedules/{schedule}/register', [PublicCourseRegistrationController::class, 'create'])
        ->name('schedules.register');

    Route::post('course-schedules/{schedule}/register', [PublicCourseRegistrationController::class, 'store'])
        ->name('schedules.register.store');
});

// ✅ Company Registration for a specific schedule
Route::get('course-schedules/{schedule}/company-register', [PublicCourseRegistrationController::class, 'companyForm'])
    ->name('schedules.company.form');

Route::post('course-schedules/{schedule}/company-register', [PublicCourseRegistrationController::class, 'companyRegister'])
    ->name('schedules.company.register');

Route::get('/company-registration/{id}/details', [PublicCourseRegistrationController::class, 'getCompanyDetails'])
    ->name('company.details');

Route::get('/contact-person/{id}/details', [PublicCourseRegistrationController::class, 'getContactDetails'])
    ->name('contact.details');


// ===============================
// COURSE SCHEDULE EVALUATION
// ===============================
Route::middleware(['auth'])->group(function () {
    Route::get('course-schedules/{schedule}/evaluation', [PublicCourseEvaluationController::class, 'create'])
        ->name('schedule.evaluation.create');

    Route::post('course-schedules/{schedule}/evaluation', [PublicCourseEvaluationController::class, 'store'])
        ->name('schedule.evaluation.store');
});

// Documents (public download)
// Route::get('documents/{document}/download', [DocumentController::class, 'download'])
//     ->name('documents.download');

// Gallery
Route::resource('galleries', PublicGalleryController::class)->only('index', 'show');

// Subscribers (newsletter)
// Route::get('subscribe', [SubscriberController::class, 'create'])->name('subscribers.create');
// Route::post('subscribe', [SubscriberController::class, 'store'])->name('subscribers.store');
// Route::get('subscribe/verify/{token}', [SubscriberController::class, 'verify'])->name('subscribers.verify');
// Route::get('unsubscribe/{token}', [SubscriberController::class, 'unsubscribe'])->name('subscribers.unsubscribe');

// About Pages
Route::get('/about/institute-profile', [PublicPageController::class, 'instituteProfile'])->name('about.institute-profile');
Route::get('/about/who-we-are', [PublicPageController::class, 'whoWeAre'])->name('about.who-we-are');

// Workshops
Route::get('/workshops/qatar', [PublicPageController::class, 'qatar'])->name('workshops.qatar');
Route::get('/workshops/process-plant-shutdown', [PublicPageController::class, 'processPlantShutdown'])->name('workshops.process-plant-shutdown');

// Schedules
Route::get('/schedules/2025-2026', [PublicCourseController::class, 'schedule2025'])->name('schedules.2025-2026');
Route::get('/schedules/2024-2025', [PublicCourseController::class, 'schedule2024'])->name('schedules.2024-2025');

// Contact Us
Route::get('/contact-us', [PublicConatactController::class, 'index'])->name('contact.us');
Route::post('/contact-us/send-message', [PublicConatactController::class, 'sendMessage'])->name('contact.send');


// Blogs
Route::resource('blogs', PublicBlogController::class)->only('index', 'show');

// Public routes
Route::prefix('blogs')->name('blog-')->group(function () {
    Route::post('{slug}/comments', [PublicBlogCommentController::class, 'store'])->name('comments.store');
    Route::post('{slug}/comments/{comment}/reply', [PublicBlogCommentController::class, 'storeReply'])->name('comments.reply');
    Route::get('{slug}/comments/{comment}/edit', [PublicBlogCommentController::class, 'edit'])->name('comments.edit');
    Route::put('{slug}/comments/{comment}', [PublicBlogCommentController::class, 'update'])->name('comments.update');
});

// Global search routes
Route::get('/global-search', [PublicSearchController::class, 'search'])
    ->name('global-search');
Route::get('/search/all', [PublicSearchController::class, 'fullSearch'])
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
| Public Routes End
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

    // Gallery
    Route::resource('galleries', AdminGalleryController::class);

    // Subscribers (only admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('subscribers', SubscriberController::class)->only(['index', 'destroy']);
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

    // Country Routes
    Route::resource('countries', AdminCountryController::class);

    // Sponsor Routes
    Route::resource('sponsors', AdminSponsorController::class);

    // Course Evaluation Questions
    Route::resource('course-evaluation-questions', AdminCourseEvaluationQuestionController::class);

    // Course Evalation Export
    Route::get('course-evaluations/{course}/export-excel/{user?}', [AdminCourseEvaluationExportController::class, 'exportExcel'])->name('course-evaluations.export.excel');
    Route::get('course-evaluations/{course}/export-pdf/{user?}', [AdminCourseEvaluationExportController::class, 'exportPdf'])->name('course-evaluations.export.pdf');

    // Users (only admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', AdminUserController::class);
    });

    // Contactus
    Route::delete('/contact-inquiries/{id}', [AdminContactController::class, 'destroy'])->name('contact.inquiries.delete');
    Route::post('/contact-inquiries/{id}/reply', [AdminContactController::class, 'sendReply'])->name('contact.inquiries.reply');
    Route::patch('/contact-inquiries/{id}/status', [AdminContactController::class, 'updateStatus'])->name('contact.inquiries.status');

    // Email handling
    // Email sending
    Route::get('emails/create', [\App\Http\Controllers\Admin\EmailController::class, 'create'])->name('emails.create');
    Route::post('emails/send', [\App\Http\Controllers\Admin\EmailController::class, 'send'])->name('emails.send');

    // Inbox
    Route::get('emails/inbox', [\App\Http\Controllers\Admin\EmailController::class, 'inbox'])->name('emails.inbox');
    Route::get('emails/view/{id}', [\App\Http\Controllers\Admin\EmailController::class, 'view'])->name('emails.view');
    Route::post('emails/reply/{id}', [\App\Http\Controllers\Admin\EmailController::class, 'reply'])->name('emails.reply');

    // Sync inbox
    Route::get('emails/sync', [\App\Http\Controllers\Admin\EmailController::class, 'syncInbox'])->name('emails.sync');

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

Route::middleware(['auth', 'verified', 'role:admin|staff|customer'])->prefix('admin')->name('admin.')->group(function () {
    // Course Registrations
    Route::resource('course-registrations', AdminCourseRegistrationController::class)->only(['index', 'show', 'update', 'destroy']);

    // Company Registrations
    Route::resource('company-registrations', CompanyRegistrationController::class)->only(['index', 'show', 'destroy']);

    // Course Evaluations
    Route::resource('course-evaluations', AdminCourseEvaluationController::class);

    // Certificates
    Route::resource('certificates', AdminCertificateController::class);
    Route::get('certificates/{certificate}/download', [AdminCertificateController::class, 'download'])->name('certificates.download');

    // Contact Enquires
    Route::get('/contact-inquiries', [AdminContactController::class, 'index'])->name('contact.inquiries');
    Route::get('/contact-inquiries/{id}', [AdminContactController::class, 'show'])->name('contact.inquiries.show');

    // Blogs Comments
    Route::resource('blog-comments', AdminBlogCommentController::class)->except(['create', 'store']);
    Route::get('blog-comments/stats', [AdminBlogCommentController::class, 'stats'])->name('blog-comments.stats');

    // Company Registrations
    Route::resource('company-registrations', AdminCompanyRegistrationController::class)->only(['index', 'show', 'update', 'destroy']);


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