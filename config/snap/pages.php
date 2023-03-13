<?php
return [
    'page_title_default' => 'Age & Peace',
    'thumbnail' => [
        'enabled' => true,
        'binary' => '/usr/local/bin/node',
        'npm_binary' => '/usr/local/bin/npm',
        'url' => 'storage/pages',
        'path' => storage_path('app/public/pages/'),
        'type' => 'jpg',
        'capture_size' => [1200, 900],
        'thumb_size' => [300, 200],
        'query_string_params' => '',
        'user_agent' => '',
        'options' => [],
    ],
    'templates' => [
        'Pages' => [
            \App\Templates\DefaultPageTemplate::class,
        ],
        'Blocks' => [
            \App\Templates\DefaultBlockTemplate::class,
        ]
        //'default' => \Snap\Website\Templates\DefaultPageTemplate::class,
        //'basic' => \Snap\Website\Templates\BasicTemplate::class,
        //'block' => \Snap\Website\Templates\DefaultBlockTemplate::class,
    ],
    'cache' => false,
    'auto_page_view_folder' => '_pages',
];