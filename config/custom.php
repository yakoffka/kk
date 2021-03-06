<?php

return [
    'store_theme' => env('STORE_THEME', 'MUSIC'),
    'products_paginate' => env('PRODUCTS_PAGINATE', 6),
    'orders_paginate' => env('ORDERS_PAGINATE', 20),
    'tasks_paginate' => env('TASKS_PAGINATE', 30),
    'events_paginate' => env('EVENTS_PAGINATE', 100),
    'num_products_seed' => env('NUM_PRODUCTS_SEED', 20),
    'num_last_events' => env('NUM_LAST_EVENTS', 50),
    'currency' => env('CURRENCY', 'BTC'),

    'main_title_append' => env('MAIN_TITLE_APPEND', config('app.name', 'Laravel')),
    'main_description' => env('MAIN_DESCRIPTION', config('app.name', 'Laravel')),
    'product_title_append' => env('PRODUCT_TITLE_APPEND', config('app.name', 'Laravel')),
    'category_title_append' => env('CATEGORY_TITLE_APPEND', config('app.name', 'Laravel')),
    'product_description_append' => env('PRODUCT_DESCRIPTION_APPEND', config('app.name', 'Laravel')),
    'category_description_append' => env('CATEGORY_DESCRIPTION_APPEND', config('app.name', 'Laravel')),

    'name_devel' => env('NAME_DEVELOP', 'DeveloperName'),
    'mail_devel' => env('MAIL_DEVELOP', 'developer@example.ex'),
    // 'pass_devel' => env('PASS_DEVELOP',     env('APP_KEY') ),
    'name_owner' => env('NAME_OWNER', 'OwnerName'),
    'mail_owner' => env('MAIL_OWNER', 'owner@example.ex'),
    // 'pass_owner' => env('PASS_OWNER',       env('APP_KEY') ),

    // EXEC_QUEUE_WORK="/opt/php/7.2/bin/php ../artisan queue:work --once"
    'exec_queue_work' => env('EXEC_QUEUE_WORK', false),

    'system_user_passw' => ENV('SYSTEM_USER_PASSW', '11111111'),
    'owner_user_passw' => ENV('OWNER_USER_PASSW', '11111111'),
    'developer_user_passw' => ENV('DEVELOPER_USER_PASSW', '11111111'),
    'admin_user_passw' => ENV('ADMIN_USER_PASSW', '11111111'),
    'cmanager_user_passw' => ENV('CMANAGER_USER_PASSW', '11111111'),
    'smanager_user_passw' => ENV('SMANAGER_USER_PASSW', '11111111'),
    'unregistered_user_passw' => ENV('UNREGISTERED_USER_PASSW', '11111111'),
    'pagination_product_admin' => ENV('PAGINATION_PRODUCT_ADMIN', 50),

    'slack_webhook' => env('SLACK_WEBHOOK', ''),
];
