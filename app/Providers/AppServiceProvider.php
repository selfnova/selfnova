<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
			return (new MailMessage)
				->subject('Проверка Email адреса')
				->greeting('Привет!')
				->line('Нажмите кнопку ниже, чтобы подтвердить Email.')
				->action('Подтвердить E-mail', $url);
		});
    }
}
