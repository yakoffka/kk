<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Category;
use App\Http\ViewComposer\{FilterManufacturerComposer, FilterCustomeventsComposer};
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // filters
        view()->composer('layouts.partials.filter-manufacturer', FilterManufacturerComposer::class);
        view()->composer('dashboard.adminpanel.partials.filters.filter-customevent', FilterCustomeventsComposer::class);

        // Sharing categories Data With All Views
        View::share('globalCategories',
            Category::with(['parent', 'children'])
                ->get()
                ->where('parent.id', '=', 1)
//                ->where('parent.seeable', '=', 'on')
                ->where('id', '>', 1)
                ->filter(static function ($value, $key) {
                    return $value->hasDescendant() && $value->fullSeeable();
                })
                ->sortBy('sort_order')
        );
    }
}
