<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PosTransactionController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard', [
        'totalProducts' => Product::count(),
        'totalCategories' => Category::count(),
        'latestProducts' => Product::with('category')->latest()->take(5)->get(),
        'latestCategories' => Category::latest()->take(5)->get(),
    ]);
})->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::get('/transactions', [PosTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{order}', [PosTransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{order}/receipt', [PosTransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::post('/add', [PosController::class, 'add'])->name('add');
    Route::patch('/update/{product}', [PosController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [PosController::class, 'remove'])->name('remove');
    Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
});
