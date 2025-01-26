<?php

namespace App\Http\Responses\Auth;

use Laravel\Fortify\Contracts\VerifyEmailViewResponse as VerifyEmailViewResponseContract;
use Illuminate\Http\Request;

class CustomVerifyEmailViewResponse implements VerifyEmailViewResponseContract
{
    public function toResponse($request)
    {
        return view('auth.verify-email', [
            'email' => $request->user()->email,
        ]);

    }
}
