<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Http;
 


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Transaction
Route::get('/orders', function () {
    return view('orders.order');
})->middleware(['auth', 'verified'])->name('order');

Route::get('/customer_service', function () {
    return view('customer_service.customer_service');
})->middleware(['auth', 'verified'])->name('customer_service');


// fetch API 
Route::get('/customer', [TransactionController::class, 'show']);
Route::get('/customer/{id}', [TransactionController::class, 'show']);


require __DIR__.'/auth.php';
