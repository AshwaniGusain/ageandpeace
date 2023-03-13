<?php
return [
    'collections' => [
        'default',
        'images',
        'category-hero',
        'post-hero',
        'provider-logo',
    ],
    'meta' => [
        'default' => \App\Admin\Meta\MediaMeta::class,
        'category-hero' => \App\Admin\Meta\MediaMeta::class,
        'post-hero' => \App\Admin\Meta\MediaMeta::class,
    ],

];
