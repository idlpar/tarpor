<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SvgController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NewsletterSubscriptionController;

use Illuminate\Support\Facades\Route;

if (!Config::get('installer.installed')) {
    Route::get('/', function () {
        return redirect()->route('install.index');
    });
}

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('throttle:5,1');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetOtp'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/resend-password-reset-otp', [AuthController::class, 'resendPasswordResetOtp'])->name('password.resend')->middleware('throttle:5,1');
    Route::get('/login/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);
    Route::get('/login/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('/login/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);
});

// OTP Verification
Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp')->middleware('throttle:5,1');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp')->middleware('throttle:5,1');
});

// Protected Routes
Route::middleware(['auth', 'auth.session', 'auto.logout'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Verified User Routes
    Route::middleware('verified')->group(function () {
        // Route::get('/my-rewards', [RewardController::class, 'showMyRewards'])->name('my.rewards');
        Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}/edit', [OrderController::class, 'userEdit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'userUpdate'])->name('orders.update');
        Route::put('/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar.update');
        Route::delete('/profile/{user}/avatar', [UserController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
        Route::put('/profile/address', [UserController::class, 'updateAddress'])->name('profile.address.update');

        // User Addresses
        Route::resource('profile/addresses', AddressController::class)->except(['show'])->names('profile.addresses');

        // User Addresses API
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('user/addresses', [AddressController::class, 'getUserAddresses']);
            Route::get('user/addresses/{address}', [AddressController::class, 'showUserAddress']);
            Route::post('user/addresses/{address}/set-default', [AddressController::class, 'setDefault']);
        });

        // Wishlist
        Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    });

    // Admin and Staff Routes
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('products', ProductController::class)->except(['show'])->names('products');

        Route::get('/admin/products/{product:id}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product:id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::get('/products/import', [ProductController::class, 'importForm'])->name('products.import');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import.store');
        Route::post('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::get('/products/bulk-edit', [ProductController::class, 'bulkEditForm'])->name('products.bulk-edit');
        Route::post('/products/bulk-edit', [ProductController::class, 'bulkUpdate'])->name('products.bulk-edit.update');
        Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');

        // Product Variant Management

        Route::get('/products/{product:id}/variants/edit', [ProductController::class, 'editVariants'])->name('products.variants.edit');
        Route::put('/products/{product:id}/variants/sync', [ProductController::class, 'syncVariants'])->name('products.variants.sync');

        // Product Attributes Management
        Route::resource('product-attributes', ProductAttributeController::class)->names('product_attributes');
        Route::prefix('product-attributes/{product_attribute}')->name('product_attributes.')->group(function () {
            Route::post('values', [ProductAttributeController::class, 'storeValue'])->name('values.store');
            Route::put('values/{value}', [ProductAttributeController::class, 'updateValue'])->name('values.update');
            Route::delete('values/{value}', [ProductAttributeController::class, 'destroyValue'])->name('values.destroy');
        });

        // Category Management
        Route::resource('categories', CategoryController::class)->except(['index', 'show']); // index and show are defined in public routes

        // Collection Management
        Route::resource('collections', CollectionController::class)->names('collections');

        // Label Management
        Route::resource('labels', LabelController::class)->names('labels');

                Route::resource('faqs', FaqController::class)->names('faqs');
        Route::resource('brands', BrandController::class)->names('brands');
        Route::patch('/brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
        Route::delete('/brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');

        Route::resource('/admin/orders', OrderController::class)->names('admin.orders');
        Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('admin.orders.update-status')
            ->middleware('can:changeStatus,order');
        Route::prefix('tag')->name('tag.')->group(function () {
            Route::get('/suggest', [TagController::class, 'suggest'])->name('suggest');
            Route::post('/store-multiple', [TagController::class, 'storeMultiple'])->name('store-multiple');
        });

        // Coupon Management
        Route::resource('coupons', CouponController::class)->names('coupons');

        // Product Specifications Management
        Route::prefix('product-specifications')->name('admin.product_specifications.')->group(function () {
            Route::resource('groups', App\Http\Controllers\ProductSpecificationGroupController::class)->names('groups');
            Route::patch('groups/{group}/restore', [App\Http\Controllers\ProductSpecificationGroupController::class, 'restore'])->name('groups.restore');
            Route::delete('groups/{group}/force-delete', [App\Http\Controllers\ProductSpecificationGroupController::class, 'forceDelete'])->name('groups.forceDelete');
            Route::resource('attributes', App\Http\Controllers\ProductSpecificationAttributeController::class)->names('attributes');
            Route::resource('tables', App\Http\Controllers\ProductSpecificationTableController::class)->names('tables');
        });

    });

    // Admin-Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->names('users');
        // Newsletter Routes
        Route::resource('admin/newsletter/subscribers', NewsletterSubscriptionController::class)->except(['create', 'store', 'show'])->names('admin.newsletter.subscribers');
        Route::patch('/admin/newsletter/subscribers/{subscriber}/toggle-status', [NewsletterSubscriptionController::class, 'toggleStatus'])->name('admin.newsletter.subscribers.toggle-status');
        Route::get('/admin/newsletter/send', [App\Http\Controllers\NewsletterController::class, 'showSendForm'])->name('admin.newsletter.send');
        Route::post('/admin/newsletter/send', [App\Http\Controllers\NewsletterController::class, 'sendMail'])->name('admin.newsletter.send.post');
    });

    // Gallery Routes (Admin and Staff) - Consolidated and Corrected
    Route::prefix('gallery')->name('gallery.')->middleware('role:admin,staff')->group(function () {
        Route::get('/', [GalleryController::class, 'index'])->name('index');
        Route::get('/api/contents', [GalleryController::class, 'getContents'])->name('getContents');
        Route::get('/trash', [GalleryController::class, 'getTrashedItems'])->name('trash');
        Route::get('/search', [GalleryController::class, 'searchItems'])->name('search');
        Route::post('/upload', [GalleryController::class, 'upload'])->name('upload');
        Route::post('/folder', [GalleryController::class, 'createFolder'])->name('folder.create');
        Route::post('/rename', [GalleryController::class, 'rename'])->name('rename');
        Route::post('/move', [GalleryController::class, 'moveItems'])->name('move');
        Route::post('/copy', [GalleryController::class, 'copyItems'])->name('copy');
        Route::post('/cut', [GalleryController::class, 'cutItems'])->name('cut');
        Route::post('/paste', [GalleryController::class, 'batchPaste'])->name('paste');
        Route::post('/delete', [GalleryController::class, 'deleteItems'])->name('delete');
        Route::post('/restore', [GalleryController::class, 'restoreItems'])->name('restore');
        Route::post('/empty-trash', [GalleryController::class, 'emptyTrash'])->name('empty-trash');
        Route::delete('/force-delete/{id}', [GalleryController::class, 'forceDelete'])->name('force-delete');

        // Item specific routes (for details, download, etc.)
        Route::get('/file/{id}', [GalleryController::class, 'showFile'])->name('file.show');
        Route::get('/file/{id}/for-insertion', [GalleryController::class, 'getFileForInsertion'])->name('file.for-insertion');
        Route::post('/files/for-insertion', [GalleryController::class, 'getFilesForInsertion'])->name('files.for-insertion');
        Route::get('/file/{id}/download', [GalleryController::class, 'downloadFile'])->name('file.download');
        Route::get('/folder/{id}', [GalleryController::class, 'showFolder'])->name('folder.show');
        Route::get('/folder-info/{id}', [GalleryController::class, 'getFolderInfo'])->name('folder.info');

        // Utility routes
        Route::get('/properties/{type}/{id}', [GalleryController::class, 'getProperties'])->name('properties');
        Route::get('/generate-url/{id}', [GalleryController::class, 'generateUrl'])->name('generate-url');
        Route::post('/context-menu', [GalleryController::class, 'getContextMenuOptions'])->name('context-menu');
        Route::post('/navigate-up', [GalleryController::class, 'navigateUp'])->name('navigate-up');
        Route::post('/set-featured/{id}', [GalleryController::class, 'setFeatured'])->name('set-featured');
        Route::post('/remove-featured/{id}', [GalleryController::class, 'removeFeatured'])->name('remove-featured');
    });
});

// Public Routes                                                                                                                                                          │
Route::get('/products/{product:slug}', [ProductController::class, 'showFrontend'])->name('products.show.frontend');
Route::get('/products/{product:id}/brief', [ProductController::class, 'brief'])->name('products.brief');
// Cart & Checkout
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'showOrderSuccess'])->name('order.success');
Route::post('/checkout/update-delivery-charge', [CheckoutController::class, 'updateDeliveryCharge'])->name('checkout.updateDeliveryCharge');
Route::put('/checkout/update-cart', [CheckoutController::class, 'updateCart'])->name('checkout.updateCart');
Route::delete('/checkout/remove-cart-item', [CheckoutController::class, 'removeCartItem'])->name('checkout.removeCartItem');

// Coupons
Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');

// Reward Points
// Route::post('/rewards/apply', [RewardController::class, 'apply'])->name('rewards.apply');

// Newsletter Routes (Public/Guest access if needed, otherwise move inside auth middleware)
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::middleware('install')->group(function () {
    Route::get('/install', [App\Http\Controllers\InstallerController::class, 'index'])->name('install.index');
    Route::get('/install/environment', [App\Http\Controllers\InstallerController::class, 'showEnvironmentForm'])->name('install.environment.form');
    Route::post('/install/environment', [App\Http\Controllers\InstallerController::class, 'saveEnvironment'])->name('install.environment');
    Route::get('/install/database', [App\Http\Controllers\InstallerController::class, 'showDatabaseForm'])->name('install.database.form');
    Route::post('/install/database', [App\Http\Controllers\InstallerController::class, 'runDatabase'])->name('install.database');
    Route::post('/install/admin', [App\Http\Controllers\InstallerController::class, 'createAdmin'])->name('install.admin');
});

// Queue Processing Route
Route::get('/queue/process', [App\Http\Controllers\QueueController::class, 'process'])->name('queue.process')->middleware('throttle:60,1');

Route::get('/', fn() => view('home'))->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/search', [ShopController::class, 'search'])->name('shop.search');
Route::post('/products/{product}/reviews', [ShopController::class, 'storeReview'])->name('products.reviews.store');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');



// Catch-All Route
Route::any('/{any}', function ($any) {
    \Log::info('Empty request detected', ['path' => $any]);
    abort(404);
})->where('any', '^(?!api).*');
