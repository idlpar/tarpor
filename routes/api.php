<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test-api-route', function () {
    return response()->json(['message' => 'Test API route works!']);
});

Route::name('api.')->group(function () {
    Route::post('images/upload', [ImageController::class, 'upload'])->name('images.upload');
    Route::get('product/slug/check', [ProductController::class, 'checkSlug'])->name('slug.check');
    Route::get('category/slug/check', [CategoryController::class, 'checkSlug'])->name('category.slug.check');
    Route::get('collection/slug/check', [CollectionController::class, 'checkSlug'])->name('collection.slug.check');
    Route::get('label/slug/check', [LabelController::class, 'checkSlug'])->name('label.slug.check');
    Route::get('brand/slug/check', [BrandController::class, 'checkSlug'])->name('brand.slug.check');
    Route::post('generate-sku', [ProductController::class, 'generateSku'])->name('sku.generate');
    Route::get('products/{product}/quick-view', [ProductController::class, 'quickView'])->name('products.quickView');
    Route::get('product/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
    Route::get('product/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('product/{product:id}/brief', [ProductController::class, 'brief'])->name('products.brief');
    Route::post('product/brief-batch', [ProductController::class, 'briefBatch'])->name('products.briefBatch');
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
});
