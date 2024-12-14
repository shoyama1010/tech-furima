<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;

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


Route::get('/', [ItemController::class, 'index'])->name('items.index');
// 会員登録処理
Route::get('/register', function () {
    return view('auth.register'); 
})->name('register');
Route::post('/register', [AuthController::class, 'register']);
// ログイン処理
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
// ログアウト処理
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [ItemController::class, 'mypage'])->name('mypage');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage/profile', [ItemController::class, 'editProfile'])->name('profile.edit');
    Route::post('/mypage/profile', [ItemController::class, 'updateProfile'])->name('profile.update');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::get('/items/detail/{id}', [ItemController::class, 'show'])->name('items.detail');

// Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');