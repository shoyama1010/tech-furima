<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use App\Http\Responses\CustomVerifyEmailViewResponse;



class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            throw ValidationException::withMessages([
                'email' => __('メールアドレスまたはパスワードが間違っています。'),
            ]);
        });

        // 会員登録処理にクラスを紐付ける
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register'); // 会員登録画面
        });

        Fortify::loginView(function () {
            return view('auth.login');  // ログイン画面
        });

        // ログイン時のレートリミット設定
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });


        // Fortify::verifyEmailView(function () {
        //     // return new CustomVerifyEmailViewResponse();
        //     return app(CustomVerifyEmailViewResponse::class);
        // });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });
        // $this->app->singleton(
        //     VerifyEmailViewResponse::class,
        //     CustomVerifyEmailViewResponse::class
        // );
    }
}
