<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CrumbsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function() {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function() {
    Route::resource('quotes', QuoteController::class)->middleware(['auth']);
    Route::resource('products', ProductController::class)->middleware(['auth']);
    Route::resource('product_images', ProductImageController::class)->middleware(['auth']);
    Route::resource('cart', CartController::class)->middleware(['auth']);
    Route::resource('crumbs', CrumbsController::class)->middleware(['auth']);
    
    Route::get('{id}/images', [ProductImageController::class, 'index'])->middleware(['auth']);
    
    Route::post('{id}/images/create', [ProductImageController::class, 'store'])->middleware(['auth']);
    
    Route::get('mail-quote/{id}', [QuoteController::class, 'mailQuote'])->middleware(['auth']);
    
    Route::patch('/quote-completed/{id}', [QuoteController::class, 'updateCompleted'])->middleware(['auth']);
    
    Route::get('/{any}', [PagesController::class, 'index'])->middleware(['auth'])->where('any', '.*');
});

require __DIR__.'/auth.php';
