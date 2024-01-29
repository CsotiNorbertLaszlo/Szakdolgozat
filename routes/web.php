<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\TransactionController;

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
    return view('login');
});

Route::get('/dashboard', [StockController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::view('/transactions', 'transactions')->name('transactions');
Route::get('/fetch-data', [StockController::class, 'fetchWeeklyAdjustedData']);
Route::post('/add-balance', [BalanceController::class, 'addBalance'])->name('add.balance');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('/stocks', [StockController::class, 'getStocks']);

Route::post('/buystocks', [TransactionController::class, 'buyStocks'])->name('buystocksADD_');



require __DIR__.'/auth.php';
