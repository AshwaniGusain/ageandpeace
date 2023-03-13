<?php

return [

    'title' => 'Admin',

    'route' => [
        'prefix'     => 'admin',
        'middleware' => ['web'],
        'namespace'  => 'App\Admin',
    ],

    'path' => app_path('Admin'),

    'login_redirect'  => 'dashboard',
    'logout_redirect' => 'admin/login',

    'modules' => [
        \Snap\Admin\Modules\DashboardModule::class,
        \App\Admin\Modules\KitchenSinkModule::class,
        \Snap\Page\Modules\PageModule::class,
        \Snap\Media\Modules\MediaModule::class,
        \Snap\Taxonomy\Modules\TaxonomyModule::class,
        \Snap\Admin\Modules\UserModule::class,
        \Snap\Docs\Modules\DocsModule::class,
        \Snap\Admin\Modules\LogModule::class,
        \Snap\Admin\Modules\SearchModule::class,
        \Snap\Navigation\Modules\NavigationModule::class,
        \Snap\Cache\Modules\CacheModule::class,
        \Snap\Setting\Modules\SettingModule::class,
    ],

    //'ui' => [
    //    'layout'                           => Snap\Admin\Ui\Layout::class,
    //    'login'                            => Snap\Admin\Ui\Auth\Login::class,
    //    'auth.password_reset_request'      => Snap\Admin\Ui\Auth\PasswordResetRequestPage::class,
    //    'auth.password_reset_form'         => Snap\Admin\Ui\Auth\PasswordResetFormPage::class,
    //    'auth.forgot'                      => Snap\Admin\Ui\Auth\ForgotPage::class,
    //    'me'                               => Snap\Admin\Ui\Auth\MePage::class,
    //    'module.dashboard'                 => Snap\Admin\Ui\Module\DashboardPage::class,
    //    'module.resource.index'            => Snap\Admin\Ui\Module\Resource\IndexPage::class,
    //    'module.resource.table'            => Snap\Admin\Ui\Module\Resource\TablePage::class,
    //    'module.resource.listing'          => Snap\Admin\Ui\Module\Resource\ListingPage::class,
    //    'module.resource.grid'             => Snap\Admin\Ui\Module\Resource\GridPage::class,
    //    'module.resource.calendar'         => Snap\Admin\Ui\Module\Resource\CalendarPage::class,
    //    'module.resource.map'              => Snap\Admin\Ui\Module\Resource\MapPage::class,
    //    'module.resource.create'           => Snap\Admin\Ui\Module\Resource\CreatePage::class,
    //    'module.resource.create_inline'    => Snap\Admin\Ui\Module\Resource\CreateInlinePage::class,
    //    'module.resource.edit'             => Snap\Admin\Ui\Module\Resource\EditPage::class,
    //    'module.resource.edit_inline'      => Snap\Admin\Ui\Module\Resource\EditInlinePage::class,
    //    'module.resource.duplicate'        => Snap\Admin\Ui\Module\Resource\DuplicatePage::class,
    //    'module.resource.duplicate_inline' => Snap\Admin\Ui\Module\Resource\DuplicateInlinePage::class,
    //    'module.resource.delete'           => Snap\Admin\Ui\Module\Resource\DeletePage::class,
    //    'module.resource.input'            => Snap\Admin\Ui\Module\Resource\InputPage::class,
    //    'module.resource.compare'          => Snap\Admin\Ui\Module\Resource\ComparePage::class,
    //],

    'media' => [
        'model_types' => [],
        'collections' => ['default', 'images'],
    ],

    //'menu' => [
    //    'admin' => ['label' => 'Admin', 'parent' => null, 'link' => false],
    //    //        'admin' => ['label' => 'Admin', 'parent' => null, 'link' => false],
    //],

    'date_format' => 'm/d/Y',

];
