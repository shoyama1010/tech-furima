<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;

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

// 認証済みユーザーのみアクセスできるルート
// Route::get('/mypage', [UserController::class, 'mypage'])
//     ->middleware(['auth', 'verified'])
//     ->name('mypage');

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
    // マイページ関連のルート
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');

    Route::get('/mypage/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    // プロフィール更新処理
    Route::put('/user/profile', [UserController::class, 'update'])->name('user-profile-information.update');

    // コメント処理
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // 購入画面表示
    Route::get('/purchase/{id}', [PurchaseController::class, 'buyitem'])->name('purchase.show');

    // 購入処理
    Route::post('/purchase/{id}', [PurchaseController::class, 'purchase'])->name('purchase.process');

    // 購入履歴の表示
    Route::get('/mypage/history', [PurchaseController::class, 'history'])->name('purchase.history');
    // 購入成功後のリダイレクト
    Route::get('/purchase/success/{id}', [PurchaseController::class, 'success'])->name('purchase.success');

    // 配送先変更画面
    Route::get('/purchase/address/{id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    // 商品出品
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    // いいね機能
    Route::post('/items/{id}/like', [ItemController::class, 'like'])->name('items.like');
});

Route::get('/items/detail/{id}', [ItemController::class, 'show'])->name('items.detail');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.detail');

Route::post('/items/{item}/toggle-like', [LikeController::class, 'toggle'])->name('items.toggle-like');


// メール確認のプロンプト（未確認のユーザー向け）
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メール内リンククリック時の処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage')->with('message', 'メール認証が完了しました！');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メールの再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

