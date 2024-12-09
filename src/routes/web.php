<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ItemController::class, 'index'])->name('items.index');

// プロフィール編集ページ
Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', function () {
        return view('auth.profile'); // プロフィール編集ビュー
    })->name('profile.show');
});
