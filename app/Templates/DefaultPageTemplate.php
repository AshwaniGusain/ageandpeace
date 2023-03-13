<?php

namespace App\Templates;

use Illuminate\Routing\Router;
use Snap\Admin\Ui\Components\Inputs\ModuleSelect;
use Snap\Form\Inputs\Repeatable;
use Snap\Form\Inputs\Text;
use Snap\Media\Ui\Components\Inputs\Media;
use Snap\Page\Templates\TemplateBase;
use Snap\Decorator\Types\NumberDecorator;
use Snap\Decorator\Types\StringDecorator;
use Snap\Form\Inputs\Currency;
use Snap\Form\Inputs\Wysiwyg;
use Snap\Page\Ui\Inputs\Template;

class DefaultPageTemplate extends TemplateBase
{
    public $name = 'Default';

    public $handle = 'default';

    public $uriPrefix = '';

    public $ui = '_templates.default';

    public function routes(Router $router)
    {
        //$router->get('events');
    }

    public function inputs()
    {
        return [
            'Content',
            Text::make('heading')->castAs(StringDecorator::class),
            Wysiwyg::make('body')->castAs(StringDecorator::class),
            'Meta',
            Text::make('page_title', ['comment' => 'If none is provided, the Heading value will be used if it exists.'])->castAs(StringDecorator::class),
            Text::make('meta_description')->castAs(StringDecorator::class),
            Text::make('og_title')->castAs(StringDecorator::class),
            Text::make('og_description', ['comment' => 'If none is provided, the Meta Description value will be used if it exists.'])->castAs(StringDecorator::class),
            Text::make('og_image')->castAs(StringDecorator::class),
        ];
    }


}