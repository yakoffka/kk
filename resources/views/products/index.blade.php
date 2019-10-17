@extends('layouts.app')


{{-- title and description --}}
    {{-- filters --}}
    @if ( !empty($appends['manufacturers']) or !empty($appends['categories']) )
        @php
            $h1 = 'Фильтрация товаров';
            $title = 'Фильтрация товаров';
            if ( $products->total() == 0 ) {
                $mess_null = 'нет товаров, удовлетворяющих заданным условиям';
            }
        @endphp

    {{-- $category --}}
    @elseif ( !empty($category) )
        @php
            $h1 = "Категория '$category->title'";
            $title = $category->name . config('custom.category_title_append');
            if ( $products->total() == 0 ) {
                $mess_null = 'в данной категории ещё нет товаров';
            }
            $description = $category->name . config('custom.category_description_append') . ' Страница ' . $products->currentPage() . ' из ' . $products->lastPage();
        @endphp

    {{-- search --}}
    @elseif ( !empty($query) )
        @php
            $h1 = 'Результаты поиска по запросу ' . '\'' . $query . '\'';
            $title = 'Найдено ' . $products->total() . ' товаров по запросу: ' . $query;
            // $description = 'Результаты поиска по запросу: ' . $query . '. Найдено ' . $products->total() . ' результатов.' . $products->total() > $products->count() ? 'Страница ' . $products->currentPage() . ' из ' . $products->lastPage() : '';
            $description = 'Результаты поиска по запросу: ' . $query . '. Найдено ' . $products->total() . ' результатов.' . 'Страница ' . $products->currentPage() . ' из ' . $products->lastPage();
            if ( $products->total() == 0 ) {
                $h1 = 'По запросу: "' . $query . '" не найдено ни одного товара';
                $mess_null = 'Сожалеем, но товаров по данному запросу не найдено. Попробуйте исправить запрос и повторить поиск еще раз.';
            }
        @endphp

    {{-- catalog all --}}
    @else
        @php
            $h1 = "Каталог товаров";
            $title = 'Каталог товаров' . config('custom.category_description_append') . ' Страница ' . $products->currentPage() . ' из ' . $products->lastPage();;
            $mess_null = '';

            if ( $products->total() == 0 ) {
                $mess_null = 'в данной категории ещё нет товаров';
            }
        @endphp
    @endif
{{-- title and description --}}


@section('title', $title)

@section('description', $description ?? config('custom.category_description_append'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @if ( !empty($category) )
                {{ Breadcrumbs::render('categories.show', $category) }}
            @else
                {{ Breadcrumbs::render('catalog') }}
            @endif
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ $h1 }}</h1>
    <div class="grey ta_r">всего товаров: {{ $products->total() }}</div>

    <div class="row">
           
            
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
           <div class="row">

                {{ $mess_null ?? '' }}

                @foreach($products as $product)

                <div class="col-lg-4 col-md-6 product_card_bm">
                    <div class="card">

                        <h2 class="product_card_h2<?php if(!$product->visible){echo ' hide';}?>"><a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->name }}</a></h2>

                        <a href="{{ route('products.show', ['product' => $product->id]) }}">
                            @if($product->images->count())
                                @php 
                                    $img = $product->images->first();
                                @endphp

                                <div 
                                    class="card-img-top b_image" 
                                    style="background-image: url({{
                                        asset('storage') . $img->path . '/' . $img->name . '-m' . $img->ext
                                    }});"
                                >
                            @else
                                <div 
                                    class="card-img-top b_image" 
                                    style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});"
                                >
                            @endif
                                <div class="dummy"></div><div class="element"></div>
                            </div>
                        </a>

                        <div class="card-body p-1">
                            <div class="card-text col-sm-12">
                                <span class="grey">
                                    @if($product->price)
                                        price: {{ $product->price }} &#8381;
                                    @else
                                        priceless
                                    @endif
                                </span>
                                <?php if(!$product->visible){echo '<span class="red">invisible</span>';}?>
                                <br>
                            </div>

                            <div class="row product_buttons center">

                                @guest

                                    <div class="col-sm-6">
                                        <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye" title="view"></i>
                                        </a>
                                    </div>
                                        
                                    @if ( config('settings.display_cart') )
                                        <div class="col-sm-6">
                                            <a href="{{ route('cart.add-item', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-cart-plus" title="to cart"></i> 
                                            </a>
                                        </div>
                                    @endif

                                @else

                                    @if ( Auth::user()->can( ['edit_products', 'delete_products'], true ) )
                                        <div class="col-sm-3 p-1">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-3 p-1">
                                            <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </div>                                       

                                        <div class="col-sm-3 p-1">
                                            {{-- <!-- form delete product -->
                                            <form action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                                                @csrf

                                                @method("DELETE")

                                                <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
                                            {{-- @modalConfirmAction(['button' => 'danger', 'cssId' => 'del_' . $product->id, 'item' => $product]) --}}
                                            @modalConfirmDestroy([
                                                'btn_class' => 'btn btn-outline-danger form-control',
                                                'cssId' => 'delele_',
                                                'item' => $product,
                                                'action' => route('products.destroy', ['product' => $product->id]), 
                                            ]) 
                                            
                                        </div>

                                    @elseif ( Auth::user()->can('edit_products') )

                                        <div class="col-sm-6 p-1">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 p-1">
                                            <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </div>
                                        
                                    @else

                                        <div class="col-sm-6 p-1">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i> view
                                            </a>
                                        </div>
                                        
                                        @if ( config('settings.display_cart') )
                                            <div class="col-sm-6 p-1">
                                                @addToCart(['product_id' => $product->id])
                                            </div>
                                        @endif

                                    @endif

                                @endguest

                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

                {{-- pagination block --}}
                @if ( empty($appends) )
                    @if($products->links())
                        <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
                    @endif
                @else
                    @if($products->appends($appends)->links())
                        <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
                    @endif
                @endif
                {{-- /pagination block --}}


            </div>
        </div>
        
    </div>{{-- <div class="row"> --}}
    
@endsection
