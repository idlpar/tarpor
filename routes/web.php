<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SvgController;
use App\Http\Controllers\TagController;
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
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
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
    Route::get('/generate-sku', [ProductController::class, 'generateSku'])->name('api.sku.generate');


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

    Route::prefix('icons')->name('icons.')->group(function () {
        Route::get('/', [SvgController::class, 'index']);
        Route::post('/cleanup', [SvgController::class, 'cleanup'])->name('cleanup');
        Route::post('/sort', [SvgController::class, 'sortSvgSymbols'])->name('sort');
    });

});


Route::any('/{any}', function ($any) {
    Log::info('Empty request detected', ['path' => $any]);
//    return response('Not Found', 404);
    abort(404);
})->where('any', '.*');

