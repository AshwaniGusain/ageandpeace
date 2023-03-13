<?php

namespace Snap\Menu\Renderer;

use Snap\Menu\MenuBuilder;

interface MenuRendererInterface
{
    public function __construct(MenuBuilder $builder);

    public function render();

    public function setOptions(array $options);
}
