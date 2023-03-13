<?php

namespace Snap\Taxonomy;

use Snap\Taxonomy\Models\Taxonomy;
use Snap\Taxonomy\Models\Term;
use Snap\Taxonomy\Models\Vocabulary;

class TaxonomyManager
{
    protected $meta = [];

    public function __construct($meta, $default)
    {
        $this->meta = $meta;
        $this->default = $default;
    }

    public function default()
    {
        if ($this->default) {
            return $this->default;
        } else {
            return $this->vocabularies()[0] ?? null;
        }
    }

    public function vocabularies()
    {
        return Vocabulary::all();
    }

    public function loadVocabularyMetaFormAssets($request = null)
    {
        static $loadedForm;

        if (is_null($loadedForm)) {
            foreach ($this->meta as $handle => $class) {
                $form = $this->vocabularyMetaForm($handle, $request);
            }

            $loadedForm = true;
        }

        return $this;
    }

    public function vocabularyMetaForm($vocabulary, $request = null)
    {
        //$vocabulary = $request->input('vocabulary_id');

        if (!$vocabulary instanceof Vocabulary) {
            if (is_numeric($vocabulary)) {
                $vocabulary = Vocabulary::where('id', '=', $vocabulary)->first();
            } elseif (is_string($vocabulary)) {
                $vocabulary = Vocabulary::where('handle', '=', $vocabulary)->first();
            }
        }

        if ($vocabulary && $request && isset($this->meta[$vocabulary->handle])) {
            $class = $this->meta[$vocabulary->handle];

            $values = [];
            if ($request->input('id')) {
                $taxonomy = Taxonomy::where('id', '=', $request->input('id'))->first();
                $values = ($taxonomy) ? $taxonomy->meta()->get() : [];
            }

            if (class_exists($class)) {
                return (new $class())->getForm($values);
            }
        }
    }

    public function createVocabulary($name)
    {
        $vocabulary = Vocabulary::firstOrNew(['name' => $name]);

        if (!$vocabulary->save()) {
            return null;
        }

        return $vocabulary;
    }

    public function getVocabulary($name)
    {
        if (is_int($name)) {
            $vocabulary = Vocabulary::find($name);
        } else{
            $vocabulary = Vocabulary::where('name', $name)->first();
        }

        return $vocabulary;
    }

    public function terms($vocabulary_id = null)
    {
        $query = Term::query();
        if ($vocabulary_id) {
            if ($vocabulary_id instanceof Vocabulary) {
                $vocabulary_id = $vocabulary_id->id;
            }

            $query->where('vocabulary_id', $vocabulary_id);
        }

        return $query->get();
    }

    //public function term($name)
    //{
    //    $query = Term::where('name', $name);
    //    //if ($vocabulary_id) {
    //    //    if ($vocabulary_id instanceof Vocabulary) {
    //    //        $vocabulary_id = $vocabulary_id->id;
    //    //    }
    //    //
    //    //    $query->where('vocabulary_id', $vocabulary_id);
    //    //}
    //
    //    $term = $query->first();
    //
    //    return $term;
    //}

    public function newTerm($vocabulary_id, $name, $parent_id = null, $params = [])
    {
        if ($vocabulary_id instanceof Vocabulary) {
            $vocabulary_id = $vocabulary_id->id;
        }

        if ($parent_id instanceof Term) {
            $parent_id = $parent_id->id;
        }

        $attributes = [
            'name' => $name,
            'vocabulary_id' => $vocabulary_id,
            'parent_id =' => $parent_id
        ];

        $term = Vocabulary::firstOrNew($attributes);


        //@TODO what to do with additional params ... SAVE TO META?

        return $term->save();
    }

    public function findOrCreateTerm($vocabulary_id, $name, $parent_id = null, $params = [])
    {
        if ($vocabulary_id instanceof Vocabulary) {
            $vocabulary_id = $vocabulary_id->id;
        }

        if ($parent_id instanceof Term) {
            $parent_id = $parent_id->id;
        }

        $attributes = [
            'name' => $name,
            'vocabulary_id' => $vocabulary_id,
            'parent_id =' => $parent_id
        ];

        $term = Vocabulary::firstOrNew($attributes);


        //@TODO what to do with additional params ... SAVE TO META?

        return $term->save();
    }

    public function createTerm($vocabulary_id, $name, $parent_id = null, $params = [])
    {
        if ($vocabulary_id instanceof Vocabulary) {
            $vocabulary_id = $vocabulary_id->id;
        }

        if ($parent_id instanceof Term) {
            $parent_id = $parent_id->id;
        }

        $term = new Term();
        $term->vocabulary_id = $vocabulary_id;
        $term->name = $name;
        $term->parent_id = $parent_id;


        //@TODO what to do with additional params ... SAVE TO META?

        return $term->save();
    }

}