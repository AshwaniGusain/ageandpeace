<?php
// Copy to config/snap/pages.php
return [
    'thumbnail' => [
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltoimage',
        'url' => 'storage/pages',
        'path' => storage_path('app/public/pages/'),
        'type' => 'jpg',
        'size' => [300, 100],
    ],
    'templates' => [
        'Pages' => [
            \App\Templates\DefaultPageTemplate::class,
        ]
        //'default' => \Snap\Website\Templates\DefaultPageTemplate::class,
        //'basic' => \Snap\Website\Templates\BasicTemplate::class,
        //'block' => \Snap\Website\Templates\DefaultBlockTemplate::class,
    ],
    'cache' => true,
    'auto_page_view_folder' => '_website',
    'page_title_separator' => ' | ',
    'page_title_default' => ''
];