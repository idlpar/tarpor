<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SvgController;
use App\Http\Controllers\TagController;
use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
})->name('home');



// Guest Dashboard (If truly for unauthenticated users)
Route::get('/guest/dashboard', function () {
    return view('dashboard.guest');
})->middleware('role:guest');


// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login') ->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
//    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
//    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
    Route::post('/resend-password-reset-otp', [AuthController::class, 'resendPasswordResetOtp'])->name('password.resend');
    // Register Routes
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetOtp'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Social Media Login Routes
    Route::get('/login/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);

    Route::get('/login/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('/login/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);

    Route::get('/shop/{produtct_slug}/product', [ShopController::class, 'productDetails'])->name('product.view');
});

// OTP Verification (Authenticated but unverified users)
Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Authenticated + Verified users)
Route::middleware(['auth', 'verified', 'auto.logout'])->group(function () {
    // Dashboards
    Route::get('/super/dashboard', function () {
        return view('dashboard.super');
    })->middleware('role:super')->name('super.dashboard'); // Named route for super dashboard

    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin.index');
    })->middleware('role:admin')->name('admin.dashboard'); // Named route for admin dashboard

    Route::get('/dashboard', function () {
        return view('dashboard.user.index');
    })->middleware('role:user')->name('user.dashboard'); // Named route for user dashboard

    // Change PasswordS
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

    // API calls
    Route::get('/product/slug/check', [ProductController::class, 'checkSlug'])->name('api.slug.check');
    Route::get('/category/slug/check', [CategoryController::class, 'checkSlug'])->name('api.category.slug.check');
    Route::post('/generate-sku', [ProductController::class, 'generateSku'])->name('api.sku.generate');


    // Product Routes (CRUD)
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('{product}/restore', [ProductController::class, 'restore'])->name('restore');
    });

    // Categories
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('tag')->name('tag.')->group(function () {
        Route::get('/suggest', [TagController::class, 'suggest'])->name('suggest');
        Route::post('/store', [TagController::class, 'store'])->name('store');
    });

    Route::prefix('gallery')->name('gallery.')->group(function () {
        // Main browsing and content management
        Route::get('/', [GalleryController::class, 'index'])->name('index');
        Route::get('/trash', [GalleryController::class, 'getTrashedItems'])->name('trash');
        Route::get('/search', [GalleryController::class, 'searchItems'])->name('search');

        // File operations
        Route::post('/upload', [GalleryController::class, 'upload'])->name('upload');
        Route::get('/folder/{id}', [GalleryController::class, 'showFolder'])->name('folder.show');
        Route::put('/folder/{id}', [GalleryController::class, 'updateFolder'])->name('folder.update');
        Route::get('/file/{id}', [GalleryController::class, 'showFile'])->name('file.show');
        Route::get('/file/{id}/for-insertion', [GalleryController::class, 'getFileForInsertion'])->name('file.for-insertion');
        Route::put('/file/{id}', [GalleryController::class, 'updateFile'])->name('file.update');
        Route::put('/file/{id}', [GalleryController::class, 'renameItem'])->name('file.rename');
        Route::delete('/file/{id}', [GalleryController::class, 'deleteFile'])->name('file.delete');

        // Folder operations
        Route::post('/folder', [GalleryController::class, 'createFolder'])->name('folder.create');
        Route::get('/folder-info/{id}', [GalleryController::class, 'getFolderInfo'])->name('folder.info');
        Route::put('/folder/{id}', [GalleryController::class, 'renameFolder'])->name('folder.rename');
        Route::delete('/folder/{id}', [GalleryController::class, 'deleteFolder'])->name('folder.delete');

        // Clipboard operations
        Route::post('/copy', [GalleryController::class, 'copyItems'])->name('copy');
        Route::post('/cut', [GalleryController::class, 'cutItems'])->name('cut');
        Route::post('/paste', [GalleryController::class, 'pasteItems'])->name('paste');

        // Batch operations
        Route::post('/move', [GalleryController::class, 'moveItems'])->name('move');
        Route::post('/batch-delete', [GalleryController::class, 'deleteItems'])->name('batch.delete');
        Route::post('/batch-restore', [GalleryController::class, 'restoreItems'])->name('batch.restore');

        // Trash operations
        Route::post('/empty-trash', [GalleryController::class, 'emptyTrash'])->name('empty-trash');

        // Properties and URLs
        Route::get('/properties/{type}/{id}', [GalleryController::class, 'getProperties'])->name('properties');
        Route::get('/generate-url/{id}', [GalleryController::class, 'generateUrl'])->name('generate-url');

        // Context menu
        Route::get('/context-menu', [GalleryController::class, 'getContextMenuOptions'])->name('context-menu');
        Route::post('/gallery/navigate-up', [GalleryController::class, 'navigateUp'])->name('navigate-up');

        // Featured media routes
        Route::post('/set-featured/{id}', [GalleryController::class, 'setFeatured'])->name('set-featured');
        Route::post('/remove-featured/{id}', [GalleryController::class, 'removeFeatured'])->name('remove-featured');

        // Force delete (bypass soft delete)
        Route::delete('/force-delete/{id}', [GalleryController::class, 'forceDelete'])->name('force-delete');
    });

    // Temporary test route
    Route::get('/test-trash', function() {
        // Should return your trashed folders (16, 20)
        $folders = MediaFolder::onlyTrashed()->get();

        // Should return your trashed files (including ID 5)
        $files = Media::onlyTrashed()->get();

        return [
            'folders' => $folders->pluck('id'),
            'files' => $files->pluck('id')
        ];
    });

    Route::prefix('icons')->name('icons.')->group(function () {
        Route::get('/', [SvgController::class, 'index']);
        Route::post('/cleanup', [SvgController::class, 'cleanup'])->name('cleanup');
        Route::post('/sort', [SvgController::class, 'sortSvgSymbols'])->name('sort');
    });

    // Run specific command
    Route::get('/setup/storage-link', function () {
        // The code inside this route will only run if the user is an admin
        \Artisan::call('storage:link');
        return 'Storage link created successfully.';
    })->middleware('role:admin'); // Apply the 'role:admin' middleware to ensure only admins can access this route

});


Route::any('/{any}', function ($any) {
    Log::info('Empty request detected', ['path' => $any]);
//    return response('Not Found', 404);
    abort(404);
})->where('any', '.*');

