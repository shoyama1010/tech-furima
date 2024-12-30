<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;

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

Route::get('/search', [ItemController::class, 'search'])->name('items.search');

Route::middleware(['guest'])->group(
    function () {
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
    }
);

// ログアウト処理 (認証済みのみ）
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // マイページ
    Route::get('/mypage', [ItemController::class, 'mypage'])->name('mypage');
    Route::get('/mypage/profile', [ItemController::class, 'editProfile'])->name('profile.edit');
    Route::post('/mypage/profile', [ItemController::class, 'updateProfile'])->name('profile.update');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // 購入画面表示
    Route::get('/purchase/{id}', [PurchaseController::class, 'buyitem'])->name('purchase.show');
    // 購入処理
    Route::post('/purchase/{id}', [PurchaseController::class, 'purchase'])->name('purchase.process');
    // 購入履歴の表示
    Route::get('/mypage/history', [PurchaseController::class, 'history'])->name('purchase.history');
    Route::get('/purchase/success/{id}', [PurchaseController::class, 'success'])->name('purchase.success');

    // 配送先変更画面
    Route::get('/purchase/address/{id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    // 商品出品
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // いいね機能
    Route::post('/items/{id}/like', [ItemController::class, 'like'])->name('items.like');

});

Route::get('/items/detail/{id}', [ItemController::class, 'show'])->name('items.detail');
Route::post('/items/{item}/toggle-like', [LikeController::class, 'toggle'])->name('items.toggle-like');

