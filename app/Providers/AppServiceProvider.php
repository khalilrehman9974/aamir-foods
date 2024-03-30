<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\FinancialYear;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
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
        Paginator::useBootstrap();

        $financialYears = FinancialYear::pluck('name','id');
        $businesses = Business::pluck('name','id');
        Cache::forever('financialYears', $financialYears);
        Cache::forever('businesses', $businesses);

    }
}
