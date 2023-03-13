<?php
// Copy to config/snap/ui.php
return [
    'data_types' => [
        // These are the default data types that are loaded:
		//''       => 'Snap\Ui\DataTypes\UiComponentType',
        //'ui'     => 'Snap\Ui\DataTypes\UiComponentType',
		//'object' => 'Snap\Ui\DataTypes\ObjectType',
		//'config' => 'Snap\Ui\DataTypes\ConfigType',
		//'view'   => 'Snap\Ui\DataTypes\ViewType',
    ],
    
    'bindings' => [
        'login'                            => \Snap\Admin\Ui\Auth\Login::class,
        'auth.password_reset_request'      => \Snap\Admin\Ui\Auth\PasswordResetRequestPage::class,
        'auth.password_reset_form'         => \Snap\Admin\Ui\Auth\PasswordResetFormPage::class,
        'auth.forgot'                      => \Snap\Admin\Ui\Auth\ForgotPage::class,
        'me'                               => \Snap\Admin\Ui\Auth\MePage::class,
        'module.dashboard'                 => \Snap\Admin\Ui\Dashboard\DashboardPage::class,
        'module.search'                    => \Snap\Admin\Ui\Module\SearchResultsPage::class,
        'module.resource.index'            => \Snap\Admin\Ui\Module\Resource\IndexPage::class,
        'module.resource.table'            => \Snap\Admin\Ui\Module\Resource\TablePage::class,
        'module.resource.listing'          => \Snap\Admin\Ui\Module\Resource\ListingPage::class,
        'module.resource.grid'             => \Snap\Admin\Ui\Module\Resource\GridPage::class,
        'module.resource.calendar'         => \Snap\Admin\Ui\Module\Resource\CalendarPage::class,
        'module.resource.map'              => \Snap\Admin\Ui\Module\Resource\MapPage::class,
        'module.resource.create'           => \Snap\Admin\Ui\Module\Resource\CreatePage::class,
        'module.resource.create_inline'    => \Snap\Admin\Ui\Module\Resource\CreateInlinePage::class,
        'module.resource.edit'             => \Snap\Admin\Ui\Module\Resource\EditPage::class,
        'module.resource.edit_inline'      => \Snap\Admin\Ui\Module\Resource\EditInlinePage::class,
        'module.resource.show'             => \Snap\Admin\Ui\Module\Resource\ShowPage::class,
        'module.resource.show_inline'      => \Snap\Admin\Ui\Module\Resource\ShowInlinePage::class,
        'module.resource.delete'           => \Snap\Admin\Ui\Module\Resource\DeletePage::class,
        'module.resource.delete_inline'    => \Snap\Admin\Ui\Module\Resource\DeleteInlinePage::class,
        'module.resource.duplicate'        => \Snap\Admin\Ui\Module\Resource\DuplicatePage::class,
        'module.resource.duplicate_inline' => \Snap\Admin\Ui\Module\Resource\DuplicateInlinePage::class,
        'module.resource.input'            => \Snap\Admin\Ui\Module\Resource\InputPage::class,
        'module.resource.compare'          => \Snap\Admin\Ui\Module\Resource\ComparePage::class,
    ],
];