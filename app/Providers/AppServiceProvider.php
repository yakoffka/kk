<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\{Category,
    Comment,
    Image,
    Manufacturer,
    Order,
    Product,
    Role,
    Services\ImportServiceInterface,
    Services\ImportService,
    Setting,
    Task,
    User
};
use App\Observers\{CategoryObserver,
    CommentObserver,
    ImageObserver,
    ManufacturerObserver,
    OrderObserver,
    ProductObserver,
    RoleObserver,
    SettingObserver,
    TaskObserver,
    UserObserver
};

use Illuminate\Support\Facades\Schema; // https://laravel-news.com/laravel-5-4-key-too-long-error part 1/2

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ImportServiceInterface::class, static function () {
            return new ImportService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Blade::component('components.alert', 'alert');

        Blade::include('includes.input', 'input');
        Blade::include('includes.textarea', 'textarea');
        Blade::include('includes.select', 'select');
        Blade::include('includes.inputImage', 'inpImage');
        Blade::include('includes.tablePermissions', 'tablePermissions');
        Blade::include('includes.modalChangeItem', 'modalChangeItem');
        Blade::include('includes.addToCart', 'addToCart');
        Blade::include('includes.select-status-order', 'selectStatusOrder');
        Blade::include('includes.modal-select', 'modalSelect');
        Blade::include('includes.modal-message', 'modalMessage');
        Blade::include('includes.modal-confirm-destroy', 'modalConfirmDestroy');
        Blade::include('includes.carousel', 'carousel');
        Blade::include('includes.listImage', 'listImage');
        Blade::include('includes.modalChangeImage', 'modalChangeImage');
        Blade::include('includes.modalForm', 'modalForm');
        Blade::include('includes.modal-textarea', 'modalTextarea');
        Blade::include('includes.category-row', 'categoryRow');
        Blade::include('includes.product-row', 'productRow');
        Blade::include('includes.lfm-image-button', 'lfmImageButton');
        Blade::include('includes.form-products-massupdate', 'formProductsMassupdate');
        Blade::include('includes.action-products-massupdate', 'actionProductsMassupdate');
        Blade::include('includes.modal-password-change', 'modalPasswordChange');

        Schema::defaultStringLength(191); // https://laravel-news.com/laravel-5-4-key-too-long-error part 2/2

        Category::observe(CategoryObserver::class);
        Comment::observe(CommentObserver::class);
        Image::observe(ImageObserver::class);
        Manufacturer::observe(ManufacturerObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        Role::observe(RoleObserver::class);
        Setting::observe(SettingObserver::class);
        Task::observe(TaskObserver::class);
        User::observe(UserObserver::class);
    }
}
