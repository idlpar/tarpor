<?php

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
use Illuminate\Support\Facades\Route;

if (!Config::get('installer.installed')) {
    Route::get('/', function () {
        return redirect()->route('install.index');
    });
}

// Public Routes
Route::get('/', fn() => view('home'))->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'productDetails'])->name('product.view');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category_slug}', [CategoryController::class, 'show'])->name('public.categories.show');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetOtp'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/resend-password-reset-otp', [AuthController::class, 'resendPasswordResetOtp'])->name('password.resend');
    Route::get('/login/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);
    Route::get('/login/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('/login/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);
});

// OTP Verification
Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
});

// Protected Routes
Route::middleware(['auth', 'auto.logout'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Verified User Routes
    Route::middleware('verified')->group(function () {
        Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}/edit', [OrderController::class, 'userEdit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'userUpdate'])->name('orders.update');
        Route::put('/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar.update');
        Route::delete('/profile/{user}/avatar', [UserController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
        Route::put('/profile/address', [UserController::class, 'updateAddress'])->name('profile.address.update');
    });

    // Admin and Staff Routes
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('products', ProductController::class)->names('products');
        Route::resource('categories', CategoryController::class)->names('categories');
        Route::resource('/admin/orders', OrderController::class)->names('admin.orders');
        Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('admin.orders.update-status')
            ->middleware('can:changeStatus,order');
        Route::prefix('tags')->name('tag.')->group(function () {
            Route::get('/suggest', [TagController::class, 'suggest'])->name('suggest');
            Route::post('/store-multiple', [TagController::class, 'storeMultiple'])->name('store-multiple');
        });
        Route::get('/product/slug/check', [ProductController::class, 'checkSlug'])->name('api.slug.check');
        Route::get('/category/slug/check', [CategoryController::class, 'checkSlug'])->name('api.category.slug.check');
        Route::post('/generate-sku', [ProductController::class, 'generateSku'])->name('api.sku.generate');
    });

    // Admin-Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->names('users');
        Route::get('/setup/storage-link', fn() => \Artisan::call('storage:link') ? 'Storage link created successfully.' : 'Failed')->name('storage.link');
        Route::prefix('icons')->name('icons.')->group(function () {
            Route::get('/', [SvgController::class, 'index']);
            Route::post('/cleanup', [SvgController::class, 'cleanup'])->name('cleanup');
            Route::post('/sort', [SvgController::class, 'sortSvgSymbols'])->name('sort');
        });
    });

    // Gallery Routes (Admin and Staff)
    Route::prefix('gallery')->name('gallery.')->middleware('role:admin,staff')->group(function () {
        Route::get('/', [GalleryController::class, 'index'])->name('index');
        Route::get('/trash', [GalleryController::class, 'getTrashedItems'])->name('trash');
        Route::get('/search', [GalleryController::class, 'searchItems'])->name('search');
        Route::post('/upload', [GalleryController::class, 'upload'])->name('upload');
        Route::get('/folder/{id}', [GalleryController::class, 'showFolder'])->name('folder.show');
        Route::put('/folder/{id}', [GalleryController::class, 'updateFolder'])->name('folder.update');
        Route::get('/file/{id}', [GalleryController::class, 'showFile'])->name('file.show');
        Route::get('/file/{id}/for-insertion', [GalleryController::class, 'getFileForInsertion'])->name('file.for-insertion');
        Route::post('/files/for-insertion', [GalleryController::class, 'getFilesForInsertion'])->name('files.for-insertion');
        Route::put('/file/{id}/rename', [GalleryController::class, 'renameItem'])->name('file.rename');
        Route::delete('/file/{id}', [GalleryController::class, 'deleteFile'])->name('file.delete');
        Route::get('/file/{id}/download', [GalleryController::class, 'downloadFile'])->name('file.download');
        Route::post('/folder', [GalleryController::class, 'createFolder'])->name('folder.create');
        Route::get('/folder-info/{id}', [GalleryController::class, 'getFolderInfo'])->name('folder.info');
        Route::put('/folder/{id}', [GalleryController::class, 'renameFolder'])->name('folder.rename');
        Route::delete('/folder/{id}', [GalleryController::class, 'deleteFolder'])->name('folder.delete');
        Route::post('/copy', [GalleryController::class, 'copyItems'])->name('copy');
        Route::post('/cut', [GalleryController::class, 'cutItems'])->name('cut');
        Route::post('/paste', [GalleryController::class, 'pasteItems'])->name('paste');
        Route::post('/batch/paste', [GalleryController::class, 'batchPaste'])->name('batch.paste');
        Route::post('/move', [GalleryController::class, 'moveItems'])->name('move');
        Route::post('/batch-delete', [GalleryController::class, 'deleteItems'])->name('batch.delete');
        Route::post('/batch-restore', [GalleryController::class, 'restoreItems'])->name('batch.restore');
        Route::post('/empty-trash', [GalleryController::class, 'emptyTrash'])->name('empty-trash');
        Route::get('/properties/{type}/{id}', [GalleryController::class, 'getProperties'])->name('properties');
        Route::get('/generate-url/{id}', [GalleryController::class, 'generateUrl'])->name('generate-url');
        Route::get('/context-menu', [GalleryController::class, 'getContextMenuOptions'])->name('context-menu');
        Route::post('/navigate-up', [GalleryController::class, 'navigateUp'])->name('navigate-up');
        Route::post('/set-featured/{id}', [GalleryController::class, 'setFeatured'])->name('set-featured');
        Route::post('/remove-featured/{id}', [GalleryController::class, 'removeFeatured'])->name('remove-featured');
        Route::delete('/force-delete/{id}', [GalleryController::class, 'forceDelete'])->name('force-delete');
        Route::post('/rename', [GalleryController::class, 'rename'])->name('rename');
    });
});

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

// Catch-All Route
Route::any('/{any}', function ($any) {
    \Log::info('Empty request detected', ['path' => $any]);
    abort(404);
})->where('any', '.*');
