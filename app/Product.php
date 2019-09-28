<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Product\ProductFilters;
use Nicolaslopezj\Searchable\SearchableTrait;
use Str;

class Product extends Model
{
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            // 'products.id' => 10,
            'products.name' => 10,
            'products.description' => 10,
        ],
    ];
    
    protected $guarded = [];

    // при раскомментировании __construct сидирование заканчивается ошибкой:
    // Illuminate\Database\QueryException  : SQLSTATE[HY000]: General error: 1364 Field 'name' doesn't have a default value (SQL: insert into `products` (`updated_at`, `created_at`) values (2019-09-05 00:58:39, 2019-09-05 00:58:39))
    // SQLSTATE[HY000]: General error: 1364 Field 'name' doesn't have a default value (SQL: insert into `products` (`updated_at`, `created_at`) values (2019-09-07 12:19:38, 2019-09-07 12:19:38))
    // public function __construct() {
    //   $this->perPage = config('custom.products_paginate');
    // }

    public function comments() {
        // return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }

    // public function category() {
    //     return $this->hasOneThrough('App\Category', 'App\CategoryProduct', 'id', 'category_id');
    // }
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    public function editor() {
        // return $this->belongsTo(User::class, 'edited_by_user_id');
        return $this->belongsTo(User::class, 'edited_by_user_id')->withDefault([
            'name' => 'NO Author'
        ]);;
    }

    public function manufacturer() {
        return $this->belongsTo(Manufacturer::class);
    }

    public function scopeFilter(Builder $builder, Request $request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new ProductFilters($request))->add($filters)->filter($builder);
    }
    
    public function images() {
        return $this->hasMany(Image::class)->orderBy('sort_order');
    }

    /**
     * Accessor
     * in blade using snake-case: $product->short_description!!!
     */
    public function getShortDescriptionAttribute()
    {
        return Str::limit(strip_tags($this->description), 80);
    }
    
    /**
     * Accessor возвращает видимость родительской категории товара
     * in controller using snake-case: $category->parent_visible!!!
     */
    public function getCategoryVisibleAttribute()
    {
        return $this->belongsTo(Category::class, 'category_id')->get()->max('visible');
    }
    
    /**
     * Accessor возвращает видимость дедовской категории товара
     * in controller using snake-case: $category->parent_visible!!!
     */
    public function getParentCategoryVisibleAttribute()
    {
        return $this->belongsTo(Category::class, 'category_id')->get()->max('parent_visible');
    }


    /**
     * Increment number of views.
     *
     * @param  Product $product
     * @return void
     */
    public function incrementViews() {
        if ( !auth()->user() or auth()->user()->hasRole('user') ) {
            $this->increment('views');
        }
    }

}
