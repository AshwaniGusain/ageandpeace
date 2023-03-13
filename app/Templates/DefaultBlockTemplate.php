<?php

namespace App\Templates;

use Illuminate\Routing\Router;
use Snap\Form\Inputs\Text;
use Snap\Page\Templates\TemplateBase;

class DefaultBlockTemplate extends TemplateBase
{
    public $name = 'Default Block';

    public $handle = 'block';

    public $uriPrefix = '';

    public $ui = '_templates.default';

    public function routes(Router $router)
    {
        //$router->get('events');
    }

    public function inputs()
    {
        return [
            Text::make('block_title', ['xrequired' => true]),
            //'Tab 1',
            //ModuleSelect::make('kitchen_sink_id', ['module' => 'kitchensink', 'placeholder' => true]),
            //Repeatable::make('sections', ['inputs' => [
            //    'title' => Text::make('title'),
            //    'nested' => ['type' => 'repeatable', 'inputs' => [
            //        'subtitle' => ['type' => 'text', 'maxlength' => '50'],
            //        'currency' => ['type' => 'currency'],
            //    ]],
            //    //'image' => Media::make('image'),
            //
            //]]),
            //Taxonomy::make('test'),
            //ModuleSelect::make('wine_id')->setModule('wine_product')->castAs(function($value, $name){
            //    return Wine::find($value);
            //}),
            //'Tab 2',
            //Wysiwyg::make('body')->castAs(StringDecorator::class),
            //Currency::make('amount')->castAs(NumberDecorator::class),
        ];
    }
}