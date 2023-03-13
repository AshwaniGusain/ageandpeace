<?php

namespace Snap\Admin\Http\Controllers;

use App\Notifications\PasswordChange;
use Illuminate\Http\Request;
use Snap\Admin\Models\Search;

class SearchController extends ModuleController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function results(Request $request)
    {
        return $this->module->ui('search', ['q' => $request->input('q')], function ($ui) {
        })->render();
    }

    public function reindex()
    {}


}
