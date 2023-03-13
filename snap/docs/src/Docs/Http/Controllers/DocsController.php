<?php

namespace Snap\Docs\Http\Controllers;

use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\AdminBaseController;
use Snap\Docs\Parser\ClassInspector;
use Snap\Docs\Ui\AutoDocsPage;
use Snap\Docs\Ui\DocsIndexPage;
use Snap\Docs\Ui\DocsPage;

class DocsController extends AdminBaseController
{
    public function index()
    {
        return new DocsIndexPage(['sections' => \Docs::sections()]);
    }

    public function package($name, $page = 'index')
    {
        return new DocsPage(['package' => $name, 'page' => $page]);
    }

    public function classDoc(Request $request)
    {
        $class = $request->get('c');

        return new AutoDocsPage(['class' => $class]);
    }


}
