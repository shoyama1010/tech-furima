<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // ユーザーを作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // メール認証メールを送信
        event(new Registered($user));
        // 自動ログイン
        Auth::login($user);
        // 認証確認ページへリダイレクト
        return redirect()->route('verification.notice')->with('success', '登録が完了しました。認証メールをご確認ください。');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = auth()->user();  // 認証されたユーザーを取得

            if (is_null($user->email_verified_at)) {
            // if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('verification.notice')->withErrors([
                    'email' => 'メール認証が完了していません。メールを確認してください。',
                ]);
            }
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
