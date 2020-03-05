<?php

namespace Tests\Feature;

use App\Category;
use App\Manufacturer;
use App\Product;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResponseTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function getUnauthorizedRequests200Test(): void
    {
        $category = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && $value->fullSeeable();
            })
            ->random();

        $product = Product::where('category_id', '=', $category->id)
            ->get()
            ->where('seeable', '=', true)
            ->random();

        $manufacturer = Manufacturer::get()->random();


        $getRequests200 = [
            '/',
            '/products/' . $product->id,
            '/categories',
            '/categories/' . $category->id,

            '/search?query=products',

            '/products?manufacturers[]=' . $manufacturer->id,
            '/products?&categories[' . $category->id . ']=' . $category->id,
            '/products?manufacturers[]=' . $manufacturer->id . '&categories[' . $category->id . ']=' . $category->id,
        ];

        echo "\nReport from " . __FILE__ . "\n";
        foreach ($getRequests200 as $route) {
            $response = $this->get($route);
            echo '    GET ' . $route;
            $response->assertStatus(200);
            echo " 200 - OK!\n";
        }
    }

    /**
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function getUnauthorizedRequests404Test(): void
    {
        $category = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && $value->fullSeeable();
            })
            ->random();

        $invisibleCategory = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && !$value->parent->seeable;
            })
            ->random();

        $invisibleSubCategory = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && !$value->seeable;
            })
            ->random();

        $invisibleProduct1 = Product::where('category_id', '=', $invisibleCategory->id)
            ->get()
            ->where('seeable', '=', true)
            ->random();

       $invisibleProduct2 = Product::where('category_id', '=', $invisibleSubCategory->id)
            ->get()
            ->where('seeable', '=', true)
            ->random();

       $invisibleProduct3 = Product::where('category_id', '=', $category->id)
            ->get()
            ->where('seeable', '=', false)
            ->random();


        $getRequests404 = [
            '/products/' . $invisibleProduct1->id,
            '/products/' . $invisibleProduct2->id,
            '/products/' . $invisibleProduct3->id,
            '/categories/' . $invisibleCategory->id,
        ];

        echo  "\n";
        foreach ( $getRequests404 as $route ) {
            $response = $this->get($route);
            echo '    GET ' . $route;
            $response->assertStatus(404);
            echo  " 404 - OK!\n";
        }
    }
}
