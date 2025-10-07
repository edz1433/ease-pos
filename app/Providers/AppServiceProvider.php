<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // $appUrl = config('app.url');

        // // Local environment (e.g. 192.168.1.x)
        // if (str_contains($appUrl, '192.168.1.')) {
        //     Config::set('session.domain', '192.168.1.21');
        //     Config::set('session.cookie', 'ease_pos_admin_session');
        //     Config::set('sanctum.stateful', ['192.168.1.21']);
        // }

        // // Production environment
        // if (str_contains($appUrl, 'kerritsolutions.com')) {
        //     Config::set('session.domain', '.kerritsolutions.com');
        //     Config::set('session.cookie', 'ease_pos_admin_session');
        //     Config::set('sanctum.stateful', [
        //         'pos.kerritsolutions.com',
        //         'admin.kerritsolutions.com',
        //     ]);
        // }
    }
}
