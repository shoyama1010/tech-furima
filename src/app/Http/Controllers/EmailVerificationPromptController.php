<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationPromptController extends Controller
{
    /**
     * メール確認ページの表示
     */
    public function show()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('status', 'メールアドレスはすでに確認済みです。');
        }

        return view('auth.verify-email');
    }
}
