<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 登録・ログイン・ログアウトの処理
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('login')->with('success', '登録が完了しました。ログインしてください。');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('items.index')->with('success', 'ログインしました。');
        }

        return back()->withErrors(['email' => 'ログイン情報が正しくありません。'])->withInput();
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'ログアウトしました。');
    }
}