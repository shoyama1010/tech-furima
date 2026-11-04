<?php

namespace App\Providers;

use App\Mail\MailtrapApiTransport;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // 既存のHTTPS強制設定を維持
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Mailtrap HTTPS API用のTransportを登録
        Mail::extend('mailtrap', function (array $config) {
            return new MailtrapApiTransport(
                new Client(),
                $config['token']
            );
        });
    }
}
