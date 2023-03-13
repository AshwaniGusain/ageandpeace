<?php

namespace Snap\Taxonomy\Modules;

use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;
use Snap\Admin\Modules\Traits\Filters\Filter;
use Snap\Admin\Ui\Components\Inputs\BelongsTo;
use Snap\Admin\Ui\Components\Inputs\ModuleSelect;
use Snap\Form\Inputs\Dependent;
use Snap\Taxonomy\Models\Taxonomy;
use Snap\Taxonomy\Models\Term;
use Snap\Taxonomy\Models\Vocabulary;

class TaxonomyModule extends ResourceModule
{
    use \Snap\Admin\Modules\Traits\FormTrait;
    use \Snap\Admin\Modules\Traits\DeletableTrait;
    use \Snap\Admin\Modules\Traits\TableTrait;
    use \Snap\Admin\Modules\Traits\ListingTrait;
    use \Snap\Admin\Modules\Traits\NavigableTrait;
    use \Snap\Admin\Modules\Traits\FiltersTrait;
    use \Snap\Admin\Modules\Traits\AjaxTrait;

    protected $parent = null;
    protected $handle = 'taxonomy';
    protected $name = 'Taxonomy';
    protected $pluralName = 'Taxonomies';
    protected $menuParent = 'admin';
    protected $menuLabel = 'Taxonomies';
    protected $menuHandle = 'taxonomy';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-sitemap';
    protected $path = __DIR__;
    protected $modules = [
        VocabularyModule::class,
        TermModule::class,
    ];
    protected $uri = null;
    protected $permissions = [];
    protected $config = null;
    protected $routes = [];
    protected $model = Taxonomy::class;
    protected $tableColumns = [
        'term.name' => 'Term',
        'vocabulary.name' => 'Vocabulary',
        'parent.term.name' => 'Parent',
        'created_at',
        'updated_at'
    ];

    public function register()
    {
        parent::register();
    }

    public function filters($filters, Request $request = null)
    {
        $filters->add(Filter::make('vocabulary_id', 'where', '=')->withInput('belongsto', ['model' => Vocabulary::class]));
    }

    public function listing($listing, Request $request)
    {

    }

    public function form($form, Request $request, $resource = null)
    {
        return $form;
    }

    public function inputs($request, $resource)
    {
        \Taxonomy::loadVocabularyMetaFormAssets($request);

        return [
            //BelongsTo::make('vocabulary_id', ['model' => Vocabulary::class, 'required' => true]),
            \Snap\Form\Inputs\BelongsTo::make('vocabulary_id', ['model' => Vocabulary::class, 'required' => true]),
            ModuleSelect::make('term_id', ['tags' => true, 'required' => true, 'placeholder' => true])
                        ->setModule('taxonomy.term')
                        ->setTagCreate(function($request){
                            $termId = $request->input('term_id');

                            if (is_numeric($termId)) {
                                $term = Term::firstOrCreate(['id' => $termId]);
                            } else {
                                $term = Term::firstOrCreate(['name' => $termId]);
                            }

                            //$taxonomy = Taxonomy::firstOrCreate(['vocabulary_id' => $request->input('vocabulary_id'), 'term_id' => $term->id]);
                            return $term->id;
                        }),
            //Dependent::make('term_id', ['url' => $this->url('ajax/vocabulary_terms'), 'source' => 'vocabulary_id', 'tags' => true])
            //            //->setModel(Term::class, false)
            //            ->setTagCreate(function($request){
            //                $term = Term::firstOrCreate(['name' => $request->input('term_id')]);
            //                //$taxonomy = Taxonomy::firstOrCreate(['vocabulary_id' => $request->input('vocabulary_id'), 'term_id' => $term->id]);
            //                return $term;
            //        })
            //,
            Dependent::make('parent_id', ['url' => $this->url('ajax/vocabulary_parent_terms'), 'source' => 'term_id']),
            Dependent::make('meta', ['label' => false, 'url' => $this->url('ajax/meta'), 'source' => 'vocabulary_id']),
            //Template::make('meta', ['value' => 'basic', 'templates' => 'basic', 'resource' => $resource]),
        ];
    }

    public function ajaxVocabularyTerms($request)
    {
        $query = Term::query();
        return $query->lists('name');
        //$query = $this->getModel()
        //            ->where('vocabulary_id', '=', $request->input('vocabulary_id'))
        //             ;
        //$vocabulary = Vocabulary::find($request->input('vocabulary_id'));
        //if ($vocabulary && $vocabulary->max_depth) {
        //    $query->where('depth', '<=', $vocabulary->max_depth);
        //}
        //
        //return $query->lists('name');
    }

    public function ajaxVocabularyParentTerms($request)
    {
        return $this->getModel()
            //->where('vocabulary_id', '=', $request->input('vocabulary_id'))
                    ->where('id', '!=', $request->input('term_id'))
                    ->lists('name')
            ;

        //if ($request->input('term_id')) {
        //    $query->where('parent_id', '!=', $request->input('term_id'));
        //}
    }

    public function ajaxMeta($request)
    {
        $form = $this->getVocabularyMetaForm($request);
        if ($form) {
            return $form->render();
        }
    }

    public function beforeSave($resource, $request)
    {
        $form = $this->getVocabularyMetaForm($request);
        if ($form) {
            $fields = $form->inputs();
            if ($fields) {
                $resource->meta()->addFields($fields)->set($request->input('meta'))->save();
            }
        }
    }

    protected function getVocabularyMetaForm($request)
    {
        return \Taxonomy::vocabularyMetaForm($request->input('vocabulary_id'), $request);
    }


}