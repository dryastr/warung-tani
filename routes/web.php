<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CustomersController;
use App\Http\Controllers\admin\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\user\AddUserController;
use App\Http\Controllers\user\ProductController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    return redirect()->route('login');
})->name('home');

Auth::routes(['middleware' => ['redirectIfAuthenticated']]);


Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::resource('transactions', TransactionController::class);
    Route::resource('history', HistoryController::class);
    Route::resource('customers', CustomersController::class);
});

Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::get('transactions-owner/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::resource('transactions-owner', TransactionController::class);
    Route::resource('add-users', AddUserController::class);
    Route::resource('history-owner', HistoryController::class);
    Route::resource('products', ProductController::class)->except(['create', 'edit']);
});
