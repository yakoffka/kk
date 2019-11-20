<h4 class="ta_c">{{ __('Categories')}}</h4>

@if($categories->count())
<ul class="navbar-nav mr-auto" id="mainMenu">
    @foreach($categories as $category)

        {{-- hide empty categories --}}
        @if ( !config('settings.show_empty_category') and !$category->countProducts() and !$category->countChildren() )
            @continue
        @endif
        {{-- /hide empty categories --}}

        <li class="nav-item" title="{{ $category->title }}">
            @if ($category->children->count())
                {{-- <a href="/products?categories[]={{ $category->id }}" --}}
                <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                    class="nav-link"
                    id="hasSub-{{ $category->id }}"
                    data-toggle="collapse"
                    data-target="#subnav-{{ $category->id }}"
                    aria-controls="subnav-{{ $category->id }}"
                    aria-expanded="false"
                {{-- >{{ $category->title }} ({{ $category->products->count() }})</a> --}}
                >{{ $category->name }} ></a>
                <ul class="navbar-collapse collapse"
                    id="subnav-{{ $category->id }}"
                    data-parent="#mainMenu"
                    aria-labelledby="hasSub-{{ $category->id }}"
                >
                    @foreach ($category->children as $subcategory)
                        {{-- hide empty subcategory --}}
                        @if ( !config('settings.show_empty_category') and !$subcategory->countProducts() and !$subcategory->countChildren() )
                            @continue
                        @endif
                        {{-- /hide empty subcategory --}}
                        <li title="{{ $subcategory->title }}">
                            <a href="{{ route('categories.show', ['category' => $subcategory->id]) }}"
                                class="nav-link">
                                {{-- {{ $subcategory->name }} ({{ $subcategory->products->count() }}) --}}
                                {{ $subcategory->name }}
                            </a>
                        </li>
                    @endforeach
                    <li><a href="{{ route('categories.show', ['gategory' => $category->id]) }}">show all</a></li>
                </ul>
            @else
                <a href="/products?categories[]={{ $category->id }}" class="nav-link">
                    {{ $category->title }} ({{ $category->products->count() }})
                </a>
            @endif
        </li>
    @endforeach
</ul>
@endif
