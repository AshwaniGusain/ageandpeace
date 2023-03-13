<?php

namespace Snap\Admin\Models;

use Snap\Database\DBUtil;
use Snap\Database\Model\Model;

class Search extends Model
{
    protected $fillable = [
        'uri',
        'title',
        'model',
        'model_id',
        'content',
        'excerpt',
        'category',
    ];

    protected $table = 'snap_search';

    public static $rules = [
        'uri'  => 'required',
        'title' => 'required',
    ];

    protected $displayNameField = 'title';

    public function scopeResults($query, $q, $limit = 10)
    {
        $fullTextFields = array('uri', 'title', 'content');
        $fullTextIndexed = implode($fullTextFields, ', ');

        $q = trim(strtolower($q)); // trim the right and left from whitespace
        $q = preg_replace("#([[:space:]]{2,})#",' ',$q); // remove multiple spaces

        $q = DBUtil::escape($q);
        $query->selectRaw('*, MATCH ('.$fullTextIndexed.') AGAINST ('.$q.') AS relevance');
        $query->orderBy('relevance', 'desc');
        $query->whereRaw('MATCH('.$fullTextIndexed.') AGAINST ('.$q.' IN BOOLEAN MODE)');

        //$query->where('content', 'like', '%'.$q.'%');

        return $query->paginate($limit);
    }

}
