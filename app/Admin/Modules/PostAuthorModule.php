<?php

namespace App\Admin\Modules;

use App\Models\PostAuthor;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FormService;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Admin\Modules\Services\SearchService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Form\Inputs\SwitchInput;

class PostAuthorModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    // use \Snap\Admin\Modules\Traits\LogTrait;
    use \Snap\Admin\Modules\Traits\RestorableTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\DuplicateTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    // use \Snap\Admin\Modules\Traits\ExportableTrait;
    // use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    // use \Snap\Admin\Modules\Traits\ViewableTrait;

    protected $parent = null;

    protected $handle = 'post_author';

    protected $name = 'Post Author';

    protected $pluralName = 'Post Authors';

    protected $menuParent = 'post';

    protected $menuLabel = 'Post Authors';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-file';

    protected $path = __DIR__;

    protected $modules = [];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = PostAuthor::class;

    public function table(TableService $table, Request $request)
    {
        $table
            ->columns(['id',
                       'name',
                       'active',
                       'updated_at',])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['name', 'active'])
            //->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active'])
        ;
    }

    public function search(SearchService $search, Request $request)
    {
        $search
            ->columns([
                'name',
            ])
        ;
    }

    public function form(FormService $form, Request $request)
    {
        $form->scaffold(['props' => ['posts' => ['update_reset_value' => false]]]);
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request, $resource = null)
    {
        $relatedInfo->moveInputs(['active']);
    }
}
