<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Product\ProductFilters;

class Product extends Model
{
    protected $guarded = [];

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

    public function scopeFilter(Builder $builder, /*Request */$request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new ProductFilters($request))->add($filters)->filter($builder);
    }
    
    public function images() {
        return $this->hasMany(Image::class)->orderBy('sort_order');
    }

}
