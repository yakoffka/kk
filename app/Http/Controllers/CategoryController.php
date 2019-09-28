<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Action, Category, Product};
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource (parent categories). 
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // show subcategories and products only directly from catalog
        if ( config('settings.display_subcategories') ) {

            $categories = Category::all()
                ->where('parent_id', '=', 1)
                ->where('visible', '=', true)
                ->where('parent_visible', '=', true) // getParentVisibleAttribute
                ->where('id', '>', 1)
                ->sortBy('sort_order');
            // dd($categories);

            $products = Product::all()
                ->where('category_id', 1);
                // ->sortBy('sort_order');
            // dd($products);

            return view('categories.index', compact('categories', 'products'));

        // show products in this category
        } else {

            // get products
            if( Auth::user() and  Auth::user()->can(['view_products'])) {
                // $products = Product::sortBy('sort_order')->paginate();
                $products = Product::paginate();
            } else {
                // $products = Product::sortBy('sort_order')->where('visible', '=', 1)->paginate();
                $products = Product::where('visible', '=', 1)->paginate();
            }

            $category = Category::find(1);

            return view('products.index', compact('products', 'category'));
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if( Auth::user()->cannot('create_categories'), 403);
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if( Auth::user()->cannot('create_categories'), 403);
        // dd(request()->all());

        $validator = request()->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
            'imagepath'     => 'nullable|string',
            'visible'       => 'nullable|string|in:on',
            'parent_id'     => 'required|integer|max:255',
        ]);

        $category = Category::create([
            'name'            => request('name'),
            'slug'            => Str::slug(request('name'), '-'),
            'title'           => request('title'),
            'description'     => request('description'),
            'visible'           => request('visible') ? 1 : 0,
            'parent_id'       => request('parent_id'),
            'added_by_user_id'=> Auth::user()->id,
        ]);

        if ( request('imagepath') ) {
            if ( !$this->attachImage($category, request('imagepath')) ) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        // add email!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'category',
            'type_id' => $category->id,
            'action' => 'create',
            'description' => 
                'Создание категории ' 
                . $category->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $category->id,
            // 'new_value' => $category->id,
        ]);

        session()->flash('message', 'Category "' . $category->name . '" with id=' . $category->id . ' was successfully created.');

        return redirect()->route('categories.show', ['category' => $category->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        abort_if( !$category->visible or !$category->parent_visible, 404);

        // перенаправление при $category->id == 1
        if ( $category->id == 1 ) {
            return redirect()->route('categories.index');
        }

        // show subcategories from this category
        if ( config('settings.display_subcategories') and $category->children->count() ) {

            $categories = Category::all()
                ->where('parent_id', $category->id)
                ->where('visible', '=', true)
                ->where('parent_visible', '=', true) // getParentVisibleAttribute
                ->sortBy('sort_order');

            $products = Product::all()->where('category_id', $category->id);
            return view('categories.index', compact('categories', 'category', 'products'));


        // show products in this category
        } else {

            // get products
            if( Auth::user() and Auth::user()->can(['view_products'])) {
                $products = Product::where('category_id', $category->id)->paginate();
            } else {
                $products = Product::where('visible', '=', 1)->where('category_id', $category->id)->paginate();
            }

            return view('products.index', compact('products', 'category'));
        }
    }

    private function getAllChildren($arr_children, $parent) {
        $arr_subchildren = $parent->children->toArray();
        foreach ( $arr_subchildren as $child ) {
            if ( $child['id'] != 1 ) {
                $arr_children[] = $child['id'];
                $parent = Category::find($child['id']);
                $arr_children = $this->getAllChildren($arr_children, $parent);
            }
        }

        return $arr_children;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        abort_if (Auth::user()->cannot('edit_categories'), 403);
        $categories = Category::all();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        abort_if( Auth::user()->cannot('edit_categories'), 403);
        // dd(request()->all());

        $validator = request()->validate([
            'name'          => 'required|string|max:255',
            'sort_order'    => 'required|string|max:1',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
            'imagepath'     => 'nullable|string',
            'visible'       => 'nullable|string|in:on',
            'parent_id'     => 'required|integer|max:255',
        ]);

        // добавить проверку успешности сохранения!
        $category->update([
            'name'              => request('name'),
            'slug'              => Str::slug(request('name'), '-'),
            'sort_order'        => request('sort_order'),
            'title'             => request('title'),
            'description'       => request('description'),
            'visible'           => request('visible') ? 1 : 0,
            'parent_id'         => request('parent_id'),
            'edited_by_user_id' => Auth::user()->id,
        ]);

        if ( request('imagepath') ) {
            if ( !$this->attachImage($category, request('imagepath')) ) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        // add email!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'category',
            'type_id' => $category->id,
            'action' => 'update',
            'description' => 
                'Редактирование категории ' 
                . $category->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $category->id,
            // 'new_value' => $category->id,
        ]);

        session()->flash('message', 'Category "' . $category->name . '" with id=' . $category->id . ' was successfully edit.');

        return redirect()->route('categories.adminshow', ['category' => $category->id]);
        // return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        abort_if ( Auth::user()->cannot('delete_categories'), 403 );

        if ( $category->id == 1 ) {
            return back()->withErrors(['"' . $category->name . '" is basic category and can not be removed.']);
        }
        
        if ( $category->countProducts() ) {

            // // перемещение содержащихся в категории товаров в каталог
            // foreach ( $category->products as $product ) {
            //     $product->update([
            //         'category_id' => 1,
            //     ]);
            // }

            //  перемещение содержащихся в категории товаров во временную категорию

            // запрет удаления категории
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        if ( $category->countChildren() ) {

            // // перемещение содержащихся в категории подкатегорий в каталог
            // foreach ( $category->products as $product ) {
            //     $product->update([
            //         'category_id' => 1,
            //     ]);
            // }

            //  перемещение содержащихся в категории подкатегорий во временную категорию

            // запрет удаления категории
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        $category->delete();

        // add email!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'category',
            'type_id' => $category->id,
            'action' => 'delete',
            'description' => 
                'Удаление категории ' 
                . $category->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $category->id,
            // 'new_value' => $category->id,
        ]);

        session()->flash('message', 'Category "' . $category->name . '" with id=' . $category->id . ' was successfully delete.');

        // return redirect()->route('categories.index');
        return back();
    }


    
    /**
     * Display a listing of the resource (all categories) for admin side. 
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex() {
        $categories = Category::all();
        return view('categories.adminindex', compact('categories'));
    }

    public function adminShow(Category $category) {
        $categories = Category::all();
        return view('categories.adminshow', compact('categories', 'category'));
    }


    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию категории
     * и обновляет запись в базе данных. 
     *
     * @return  boolean
     */
    private function attachImage (Category $category, $imagepath) {
        // костыль... не совсем разобрался с тонкостями Filesystem
        $src = str_replace( config('filesystems.disks.lfm.url'), '', $imagepath );
        $dst_dir = 'images/categories/' . $category->id;
        $basename = basename($src);
        $dst = $dst_dir . '/' . $basename;

        // проверка на существование исходного файла. так как config('filesystems.disks.lfm.root') === config('filesystems.disks.public.root'), использую не 'Storage::disk(config('lfm.disk'))->exists($src)', а 'Storage::disk('public')->exists($src)'
        if ( !Storage::disk('public')->exists($src) ) {
            return back()->withErrors(['something wrong. err' . __line__])->withInput();
        }

        // удаление всех файлов из директории назначения
        $arr_files = Storage::disk('public')->files($dst_dir);
        if ( $arr_files ) {
            if ( $arr_files = Storage::disk('public')->delete($arr_files) ) {
                // dd("all files in dir $dst_dir has been deleted");
            } else {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        // копирование файла
        if ( !Storage::disk('public')->copy($src, $dst) ) {
            return back()->withErrors(['something wrong. err' . __line__])->withInput();
        }

        // update $category
        if ( !$category->update(['image' => $basename]) ) {
            return back()->withErrors(['something wrong. err' . __line__])->withInput();
        }

        return true;
    }
}
