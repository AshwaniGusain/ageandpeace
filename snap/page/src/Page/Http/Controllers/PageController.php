<?php

namespace Snap\Page\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ResourceModuleController;
use Snap\Admin\Http\Requests\ResourceRequest;
use Snap\Page\Page;
use Snap\Form\Form;
use Snap\Page\UrlManager;

class PageController extends ResourceModuleController
{
    /**
     * Overwritten to look at the templates form.
     *
     * @param $input
     * @param null $resource
     * @return mixed
     */
    public function input($input, $resource = null)
    {
        $template = \Template::get($this->request->input('type'));

        $form = $template->getForm();
        return $this->module->ui('input', [
            'input'    => substr($input, strlen('meta') + 1),
            'resource' => $resource,
            'value'    => $this->request->input('value'),
            'form'     => $form,
        ], function ($ui) use ($resource) {

        })->render();
    }

    public function template($template, $resource = null)
    {
        $template = \Template::get($template);

        $metaScope = request()->input('scope', 'meta');
        $data = ($resource) ? (array) $resource->meta()->get() : [];

        $data = array_merge($data, old('meta', []));

        $key = Form::createKey($metaScope);

        //dd($key);
        //$key = request()->input('key');
        if ($key) {
            $keyParts = explode('.', $key);
            if (count($keyParts) > 1) {
                array_shift($keyParts);
                $data = array_get($data, implode('.', $keyParts));
            }
        }

        if ($template) {
            return [
                'handle' => $template->handle(),
                'name' => $template->name(),
                'form' => $template->getForm()
                                   ->assign('scope', $metaScope.'[]')
                                   ->withValues($data)
                                   ->setTemplate('admin::components.form')
                                   ->render()
                ,
                //'ui' => $this->render(),
                //'fields' => $this->inputs(),
                'uriPrefix' => $template->getPrefix(),
            ];
        }

        return '';
    }

    public function pages($uri = '/', Request $request)
    {
        // This is where we add preview variables from the Admin
        $vars = \Auth::user() ? $request->input('meta', []) : [];
        $page = Page::make($uri, $vars);

        $output = $page->render();
        if (!$output) {
            abort(404);
        }

        return $output;
    }

    public function makeThumb($resource, $generate = false)
    {
        $thumb = $resource->thumb;

        //if ($thumb->exists()) {
        if ($generate && !$thumb->generate()) {
            //return 'Error making thumbnail.';
        }

        //header("Content-Length: ".$thumb->fileSize(), true); // causes an error
        header("Content-Type: ".$thumb->mime(), true);

        return readfile($thumb->path());
        //}

        // @TODO... throw error?
    }

    public function urls(Request $request)
    {
        $urls[] = ['name' => 'Select...', 'url' => false];
        foreach (\PublicUrls::get() as $url) {
            $name = $url;
            if ($request->input('withFunc')) {
                $url = ($url);
            }

            $urls[] = ['name' => $name, 'url' => $url];
        }

        return $urls;
    }
}