{{-- 
        <div class="main_menu ">
            <a href="/home">Home</a>
            <a href="/products">Products</a>
            <a href="https://gitter.im/LaravelRUS/chat#" target="_blank">RusLaravelChat</a>
            <a href="https://yakoffka.ru/laravel/laravel_online_storefront_01">Logs</a>
            <?php
                if (Auth::user() and Auth::user()->can('create_products')) {
                    echo '<a href="/products/create">New Product</a>'."\n";
                }
            ?>
            <?php
                // if (Auth::user() and Auth::user()->can('create_products')) {
                //     echo '<a href="/clear">CacheClear</a>'."\n";
                // }
            ?>
            <?php
                // echo '<a href="/categories">Catalog</a>'."\n";
            ?>
            <?php
                if (Auth::user() and Auth::user()->can('view_users')) {
                    echo '<a href="/users">Users</a>'."\n";
                }
            ?>
            <?php
                if (Auth::user() and Auth::user()->can('view_roles')) {
                    echo '<a href="/roles">Roles</a>'."\n";
                }
            ?>
            <?php
                if (Auth::user() and Auth::user()->can('view_settings')) {
                    // echo '<a href="{{ route(\'settings.index\') }}">Settings</a>'."\n"; TODO!
                    echo '<a href="/settings">Settings</a>'."\n";
                }
            ?>
            <a href="https://github.com/yakoffka/kk" target="_blank">GitHub</a>
        </div>
        <div class="main_menu ">
            <a href="/home">Home</a>
            <a href="/products">Products</a>
            <a href="https://gitter.im/LaravelRUS/chat#" target="_blank">RusLaravelChat</a>
            <a href="https://yakoffka.ru/laravel/laravel_online_storefront_01">Logs</a>
            <?php
                if (Auth::user() and Auth::user()->can('create_products')) {
                    echo '<a href="/products/create">New Product</a>'."\n";
                }
            ?>
            <?php
                // if (Auth::user() and Auth::user()->can('create_products')) {
                //     echo '<a href="/clear">CacheClear</a>'."\n";
                // }
            ?>
            <?php
                // echo '<a href="/categories">Catalog</a>'."\n";
            ?>
            <?php
                if (Auth::user() and Auth::user()->can('view_users')) {
                    echo '<a href="/users">Users</a>'."\n";
                }
            ?>
            <?php
                if (Auth::user() and Auth::user()->can('view_roles')) {
                    echo '<a href="/roles">Roles</a>'."\n";
                }
            ?>
            <?php
                if (Auth::user() and Auth::user()->can('view_settings')) {
                    // echo '<a href="{{ route(\'settings.index\') }}">Settings</a>'."\n"; TODO!
                    echo '<a href="/settings">Settings</a>'."\n";
                }
            ?>
            <a href="https://github.com/yakoffka/kk" target="_blank">GitHub</a>
        </div> --}}



        <div class="main_menu ">
            <a href="/home">Home</a>
            <a href="/products">Catalog</a>
            <a href="https://github.com/yakoffka/kk" target="_blank">GitHub</a>
        </div>