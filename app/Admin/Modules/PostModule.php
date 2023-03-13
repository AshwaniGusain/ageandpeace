<?php

namespace App\Admin\Modules;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Services\FiltersService;
use Snap\Admin\Modules\Services\FormService;
use Snap\Admin\Modules\Services\IndexableService;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Admin\Modules\Services\SearchService;
use Snap\Admin\Modules\Services\TableService;
use Snap\Admin\Modules\Traits\Filters\Filter;

class PostModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\SearchTrait;
    use \Snap\Admin\Modules\Traits\ScopesTrait;
    use \Snap\Admin\Modules\Traits\GridTrait;
    use \Snap\Admin\Modules\Traits\ListingTrait;
    use \Snap\Admin\Modules\Traits\CalendarTrait;
    use \Snap\Admin\Modules\Traits\FiltersTrait;
    use \Snap\Admin\Modules\Traits\IndexableTrait;
    use \Snap\Admin\Modules\Traits\PreviewTrait;
    use \Snap\Admin\Modules\Traits\RelatedInfoTrait;

    use \Snap\Admin\Modules\Traits\LogTrait;
    use \Snap\Admin\Modules\Traits\RestorableTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\OthersNavigationTrait;

    // use \Snap\Admin\Modules\Traits\ExportableTrait;
    // use \Snap\Admin\Modules\Traits\RelatedInfoTrait;
    // use \Snap\Admin\Modules\Traits\ViewableTrait;

    protected $parent = null;

    protected $handle = 'post';

    protected $name = 'Post';

    protected $pluralName = 'Posts';

    protected $menuParent = 'admin';

    protected $menuLabel = 'Posts';

    protected $description = '';

    protected $version = '1.0.0';

    protected $icon = 'fa fa-file';

    protected $path = __DIR__;

    protected $modules = [
        PostAuthorModule::class,
    ];

    protected $uri = null;

    protected $config = null;

    protected $routes = [];

    protected $model = Post::class;

    protected $publicBaseUri = 'news';

    public function query($query)
    {
        $query->with(['category', 'image']);
    }

    public function table(TableService $table, Request $request)
    {
        $table
            ->columns(['id',
                       'sm_image_url' => 'Image',
                       'title',
                       'category.name' => 'Category',
                       'author.name' => 'Author',
                       'status',
                       'featured',
                       'precedence',
                       'updated_at',])
            ->nonSortable('sm_image_url')
            ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['featured'])
            ->format(function($value, $data, $key){
                return '<img src="'.$value.'" alt="" height="80">';
            }, ['sm_image_url'])
            ->format('\Snap\Admin\Ui\Formatters\EditInline::format', ['title', 'category.name', 'author.name', 'status', 'featured', 'precedence'])
        ;
    }

    public function filters(FiltersService $filters, Request $request)
    {
        $filters
            ->add(Filter::make('category_id', 'where')->withInput('select', [
                'label' => 'Category',
                'options' => Category::subCategories()->orderBy('name')->lists('name'),
                'placeholder' => true,
                ])
            )->add(Filter::make('status', 'where')->withInput('select', [
                'label' => 'Status',
                'options' => ['draft', 'published', 'unpublished'],
                'placeholder' => true,
            ])
            )
        ;
    }

    public function search(SearchService $search, Request $request)
    {
        $search
            ->columns([
                'title',
                'body',
                'category.name',
                'status'
                ])
        ;
    }

    public function getListingItems()
    {
        $data = array();
        $items = $this->getQuery()->get();
        foreach ($items as $item) {
            $data[$item->id] = $item;
            if ($item->category_id) {
                $data[$item->category->id] = $item->category;
                $data[$item->category->id]['parent_id'] = null;
                $data[$item->id]['parent_id'] = $item->category->id;
            }
        }

        return $data;
    }

    public function form(FormService $form, Request $request)
    {
        $form->scaffold();
        $form->get('slug')->setBoundTo('#title')
                          //->setPrefix('news/')
        ;
        $form->addMedia('image', [
            'options'  => ['collection' => 'post-hero'],
            'multiple' => false,
        ]);

        $form->get('category_id')->setModule('category');
        $form->get('author_id')->setModule('post.post_author');
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request, $resource = null)
    {
        $relatedInfo->moveInputs(['featured', 'status', 'precedence']);
    }

    protected function indexable(IndexableService $indexable, Request $request)
    {
        $indexable->fields(['name', 'email', 'street', 'zip', 'phone', 'website']);
    }

}
