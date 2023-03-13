<?php

return [

    'title' => 'Age & Peace',

    'route' => [
        'prefix'     => 'snap',
        //'middleware' => ['web', 'role:super-admin'],
        'middleware' => ['web'],
        'namespace'  => 'App\Admin',
    ],

    'path' => app_path('Admin'),

    'login_redirect'  => 'dashboard',
    'logout_redirect' => 'snap/login',

    'modules' => [
        \Snap\Admin\Modules\DashboardModule::class,
        \App\Admin\Modules\CompanyModule::class,
        \App\Admin\Modules\ProviderModule::class,
        \App\Admin\Modules\CustomerModule::class,
        \App\Admin\Modules\PostModule::class,
        //\App\Admin\Modules\PostAuthorModule::class,
        \App\Admin\Modules\TaskModule::class,
        \App\Admin\Modules\CategoryModule::class,
        //\App\Admin\Modules\InviteModule::class,
        \App\Admin\Modules\MembershipTypeModule::class,
        //\App\Admin\Modules\ProviderLabelModule::class,
        //\App\Admin\Modules\KitchenSinkModule::class,
        \Snap\Page\Modules\PageModule::class,
        \Snap\Media\Modules\MediaModule::class,
        //\Snap\Taxonomy\Modules\TaxonomyModule::class,
        \Snap\Admin\Modules\UserModule::class,
        \Snap\Docs\Modules\DocsModule::class,
        \Snap\Admin\Modules\LogModule::class,
        \Snap\Admin\Modules\SearchModule::class,
        //\Snap\Navigation\Modules\NavigationModule::class,
        //\Snap\Setting\Modules\SettingModule::class,
        \Snap\Cache\Modules\CacheModule::class,
    ],

    'dashboard' => [
        //\Snap\Analytics\Ui\Dashboard\AnalyticsWidget::class,
        [
            \Snap\Admin\Ui\Dashboard\ActivityWidget::class,
            \Snap\Admin\Ui\Dashboard\SessionsWidget::class,
        ],
    ],

    //'ui' => [
    //    'layout'                           => Snap\Admin\Ui\Layout::class,
    //    'login'                            => Snap\Admin\Ui\Auth\Login::class,
    //    'auth.password_reset_request'      => Snap\Admin\Ui\Auth\PasswordResetRequestPage::class,
    //    'auth.password_reset_form'         => Snap\Admin\Ui\Auth\PasswordResetFormPage::class,
    //    'auth.forgot'                      => Snap\Admin\Ui\Auth\ForgotPage::class,
    //    'me'                               => Snap\Admin\Ui\Auth\MePage::class,
    //    'module.dashboard'                 => Snap\Admin\Ui\Module\DashboardPage::class,
    //    'module.search'                    => Snap\Admin\Ui\Module\SearchResultsPage::class,
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
    //    'module.resource.delete'           => Snap\Admin\Ui\Module\Resource\DeletePage::class,
    //    'module.resource.delete_inline'    => Snap\Admin\Ui\Module\Resource\DeleteInlinePage::class,
    //    'module.resource.duplicate'        => Snap\Admin\Ui\Module\Resource\DuplicatePage::class,
    //    'module.resource.duplicate_inline' => Snap\Admin\Ui\Module\Resource\DuplicateInlinePage::class,
    //    'module.resource.input'            => Snap\Admin\Ui\Module\Resource\InputPage::class,
    //    'module.resource.compare'          => Snap\Admin\Ui\Module\Resource\ComparePage::class,
    //],

    //'media' => [
    //    'model_types' => [
    //        \App\Models\Post::class     => 'Post',
    //        \App\Models\Provider::class => 'Provider',
    //        \App\Models\Customer::class => 'Customer',
    //    ],
    //    'collections' => ['default', 'images'],
    //
    //],

    //'menu' => [
    //    'products' => ['label' => 'Products', 'parent' => null, 'link' => false],
    //    'taxonomy' => ['label' => 'Taxonomy', 'parent' => null, 'link' => false],
    //    //'reports'  => ['label' => 'Reports', 'parent' => null, 'link' => false],
    //    'admin'    => ['label' => 'Admin', 'parent' => null, 'link' => false],
    //    //        'admin' => ['label' => 'Admin', 'parent' => null, 'link' => false],
    //],

    'date_format' => 'm/d/Y',
    'time_format' => 'h:i a',

];
