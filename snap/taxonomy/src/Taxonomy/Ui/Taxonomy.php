<?php

namespace Snap\Taxonomy\Ui;

use Snap\Admin\Ui\Components\Inputs\BelongsToMany;
use Snap\Taxonomy\Facades\Taxonomy as Tax;
use Snap\Taxonomy\Models\Term;
use Snap\Taxonomy\Models\Vocabulary;

class Taxonomy extends BelongsToMany
{
    protected $data = [
        'placeholder' => true,
    ];

    protected $vocabulary;

    public function initialize()
    {
        parent::initialize();

        //$this->setTags(true);

        if (!$this->vocabulary) {
            $this->setVocabulary(Tax::default());
        }

        //$this->setModel(\Snap\Taxonomy\Models\Taxonomy::class, false);
        $this->setModule('taxonomy');

        // From ModuleSelect
        if ($this->vocabulary) {
            $this->setUrlParams(['vocabulary_id' => $this->vocabulary->id]);
        }

        parent::initialize();
    }

    //protected function getTaxonomy($termId)
    //{
    //    if ( ! is_numeric($termId)) {
    //        $vocabulary = Vocabulary::where('handle', '=', $this->vocabulary)->first();
    //        $term = Term::firstOrCreate(['name' => $termId]);
    //        $taxonomy = Taxonomy::firstOrCreate(['term_id' => $term->id, 'vocabulary_id' => $vocabulary->id])->getKey();
    //    }
    //    return $taxonomy;
    //}


    public function setVocabulary($vocabulary)
    {
        if (!$vocabulary instanceof Vocabulary) {
            if (!is_int($vocabulary)) {
                $vocabulary = Vocabulary::where('handle', '=', $vocabulary)
                                        ->orWhere('name', $vocabulary)
                                        ->first()
                ;
            } else {
                $vocabulary = Vocabulary::where('id', '=', $vocabulary)->first();
            }
        }

        $this->vocabulary = $vocabulary;

        return $this;
    }

    public function getVocabulary()
    {
        return $this->vocabulary;
    }

    public function getOptions()
    {
        $options = [];
        //$options = parent::getOptions();
        if ($this->vocabulary) {
            $options = \Snap\Taxonomy\Models\Taxonomy::where('vocabulary_id', $this->vocabulary->id)->lists('term.name');
        }
        //$options = \Snap\Taxonomy\Models\Taxonomy::whereHas('vocabulary', function($query){
        //    $query->where('name', '=', $this->vocabulary);
        //})->lists('term.name');

        return $options;
    }

    //protected function _render()
    //{
    //    // Must set it this way because we are overwriting the data property
    //    $this->data['value'] = $this->getValue();
    //
    //    $this->with(
    //        [
    //            'options' => $this->getOptions(),
    //        ]
    //    );
    //
    //    $this->setAttrs(
    //        [
    //            'name'     => $this->getName(),
    //            'id'       => $this->getId(),
    //            'multiple' => $this->getMultiple(),
    //            'class'    => $this->getAttr('class') ? $this->getAttr('class') : ($this->getAttr('class') !== false ? 'form-control' : ''),
    //        ]
    //    );
    //
    //    return parent::_render();
    //}
}