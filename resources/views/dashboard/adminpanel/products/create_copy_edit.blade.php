@extends('layouts.theme_switch')
@section('title', __($actions['type'] . '_product') . ' ' . (isset($product) ? $product->name : ''))
@section('description', __($actions['type'] . '_product') . ' ' . (isset($product) ? $product->name : ''))
@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @if ( isset($product) )
                {{ Breadcrumbs::render('products.' . $actions['type'], $product) }}
            @else
                {{ Breadcrumbs::render('products.create') }}
            @endif
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    <h1>{{__(ucfirst($actions['type']) . '_product')}} {{ isset($product) ? $product->name : '' }}</h1>

    <div class="row">

        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

            @if ( isset($product) )
                @listImage(compact('product'))
            @endif

            <form method="POST" action="{{ $actions['action'] }}" enctype="multipart/form-data">
                @csrf
                @method($actions['method'])

                @if ( $actions['type'] === 'copy' )
                    <div class="boxes left_stylized_checkbox">
                        <input type="checkbox" id="copy_img" name="copy_img" value="{{ $product->id }}" checked>
                        <label for="copy_img">{{ __('Copy_images_product_donor') }}</label>
                    </div>
                @endif

                @lfmImageButton(['id' => 'lfm_images', 'name' => 'images_path', 'value' => old('images_path') ?? ''])

                <div class="row">

                    {{-- name --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="name">{{ __('__namerequired') }}*</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{__('Name')}}"
                                value="{{ old('name') ?? (isset($product) ? $product->name : '') }}" required>
                        </div>
                    </div>
                    {{-- /name --}}

                    {{-- title --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="title">{{ __('title') }}</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="{{__('Title')}}"
                                value="{{ old('title') ?? (isset($product) ? $product->title : '') }}">
                        </div>
                    </div>
                    {{-- /title --}}

                    {{-- slug --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="slug">{{ __('slug') }}</label>
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="{{__('Slug')}}"
                                value="{{ old('slug') ?? (isset($product) ? $product->slug : '') }}">
                        </div>
                    </div>
                    {{-- /slug --}}
                </div>

                {{-- manufacturer, materials, date_manufactured, price --}}
                <div class="row">

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="manufacturer_id">{{ __('manufacturer') }}</label><br>
                            <select name="manufacturer_id" id="manufacturer_id">
                                @foreach ( $manufacturers as $manufacturer )
                                    @if ( isset($product) && $product->manufacturer_id === $manufacturer->id )
                                        <option value="{{$manufacturer->id}}" selected>{{$manufacturer->title}}</option>
                                    @else
                                        <option value="{{$manufacturer->id}}">{{$manufacturer->title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        @input(['name' => 'materials', 'label' => __('materials'), 'value' => old('materials') ?? (isset($product) ? $product->materials : '')])
                    </div>

                    <div class="col-12 col-md-3">
                        @input(['name' => 'date_manufactured', 'label' => __('date_manufactured'), 'type' => 'date', 'value' => old('date_manufactured') ?? (isset($product) ? $product->date_manufactured : '')])
                    </div>

                    <div class="col-12 col-md-3">
                        @input(['name' => 'price', 'type' => 'number', 'label' => __('price'), 'value' => old('price') ?? (isset($product) ? $product->price : '')])
                    </div>

                </div>
                {{-- manufacturer, materials, date_manufactured, price --}}

                {{-- description --}}
                @if ( config('settings.description_wysiwyg') === 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'description', 'label' => 'Описание (редактор ckeditor)', 'value' => old('description') ?? (isset($product) ? $product->description : '' )])
                @elseif ( config('settings.description_wysiwyg') === 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'description', 'label' => 'Описание (редактор summernote)', 'value' => old('description') ?? (isset($product) ? $product->description : '' )])
                @elseif ( config('settings.description_wysiwyg') === 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'description', 'label' => 'Описание (редактор tinymce)', 'value' => old('description') ?? (isset($product) ? $product->description : '' )])
                @else
                    @textarea(['name' => 'description', 'label' => 'Описание (обычный режим)', 'value' => old('description') ?? (isset($product) ? $product->description : '' )])
                @endif
                {{-- /description --}}


                {{-- modification --}}
                @if ( config('settings.modification_wysiwyg') === 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор ckeditor)', 'value' => old('modification') ?? (isset($product) ? $product->modification : '' ) ])
                @elseif ( config('settings.modification_wysiwyg') === 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор summernote)', 'value' => old('modification') ?? (isset($product) ? $product->modification : '' ) ])
                @elseif ( config('settings.modification_wysiwyg') === 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор tinymce)', 'value' => old('modification') ?? (isset($product) ? $product->modification : '' ) ])
                @elseif ( config('settings.modification_wysiwyg') === 'srctablecode' )
                    @textarea(['name' => 'modification', 'label' => 'Модификации (режим исходного кода таблицы)', 'value' => old('modification') ?? (isset($product) ? $product->modification : '' ) ])
                @else
                    @textarea(['name' => 'modification', 'label' => 'Модификации (обычный режим)', 'value' => old('modification') ?? (isset($product) ? $product->modification : '' ) ])
                @endif
                {{-- /modification --}}


                {{-- workingconditions --}}
                @if ( config('settings.workingconditions_wysiwyg') === 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор ckeditor)', 'value' => old('workingconditions') ?? (isset($product) ? $product->workingconditions : '' ) ])
                @elseif ( config('settings.workingconditions_wysiwyg') === 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор summernote)', 'value' => old('workingconditions') ?? (isset($product) ? $product->workingconditions : '' ) ])
                @elseif ( config('settings.workingconditions_wysiwyg') === 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор tinymce)', 'value' => old('workingconditions') ?? (isset($product) ? $product->workingconditions : '' ) ])
                @else
                    @textarea(['name' => 'workingconditions', 'label' => 'Условия работы (обычный режим)',  'value' => old('workingconditions') ?? (isset($product) ? $product->workingconditions : '' ) ])
                @endif
                {{-- /workingconditions --}}


                <div class="row">
                    {{-- sort_order --}}
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="sort_order">{{ __('sort_order') }}</label>
                            <select name="sort_order" id="sort_order">
                                @for ( $i = 0; $i < 10; $i++ )
                                    @if ( isset($product) && $product->sort_order === $i )
                                        <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                    {{-- sort_order --}}

                    {{-- parent category --}}
                    <div class="col-12 col-md-7">
                        <div class="form-group">
                            <label for="category_id">{{ __('category') }}</label>
                            <select name="category_id" id="category_id">
                                @foreach ( $catalog as $child_category )
                                    @if ( $child_category->subcategories )
                                        @foreach ( $child_category->subcategories as $childCategory )
                                            @include('dashboard/adminpanel/include/child_category_option', [
                                                'child_category' => $childCategory,
                                                'prefix' => ''
                                            ])
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- /parent category --}}

                    {{-- publish --}}
                    <div class="form-group col-12 col-md-2">
                        <div class="right_stylized_checkbox">
                            <input type="checkbox" id="publish" name="publish"
                                @if ( isset($product) && !$product->publish )
                                @else
                                    checked
                                @endif
                            >
                            <label for="publish">{{ __('publish') }}</label>
                        </div>
                    </div>
                    {{-- /publish --}}
                </div>

                <button type="submit" class="btn btn-primary form-control">{{ __('apply') }}</button>

            </form>
        </div>
    </div>
@endsection
