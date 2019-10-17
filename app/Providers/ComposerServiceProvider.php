<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Category;
use App\Http\ViewComposer\{NavigationComposer, FilterManufacturerComposer, FilterCategoryComposer, FilterActionsComposer};

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // nav
        view()->composer('layouts.partials.nav', NavigationComposer::class);

        // filters product
        view()->composer('layouts.partials.filter-manufacturer', FilterManufacturerComposer::class);
        view()->composer('layouts.partials.filter-category', FilterCategoryComposer::class);
        // filters action
        view()->composer('dashboard.adminpanel.partials.filters.filter-action', FilterActionsComposer::class);
    }
}
