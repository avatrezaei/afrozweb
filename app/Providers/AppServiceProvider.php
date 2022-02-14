<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Pagination\Paginator;
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


    }
}
