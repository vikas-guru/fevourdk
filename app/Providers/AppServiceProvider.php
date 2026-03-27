<?php

namespace App\Providers;

use App\Contracts\SmsSender;
use App\Services\OtpService;
use App\Services\Sms\LogSmsSender;
use App\Services\Sms\Msg91SmsSender;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsSender::class, function () {
            if (config('services.sms.driver') === 'msg91' && filled(config('services.sms.msg91.auth_key'))) {
                return new Msg91SmsSender(
                    (string) config('services.sms.msg91.auth_key'),
                    (string) config('services.sms.msg91.sender_id'),
                    (string) config('services.sms.msg91.route', '4'),
                );
            }

            return new LogSmsSender();
        });

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService($app->make(SmsSender::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
