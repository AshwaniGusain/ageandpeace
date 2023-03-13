<?php

namespace Snap\Menu\Renderer;

use Snap\Menu\MenuBuilder;

interface NestedMenuRendererInterface
{
    public function __construct(MenuBuilder $builder);

    public function render($items, $level);

    public function setOptions(array $options);

    public function getParent();

    public function setParent($parent);

    public function getDepth();

    public function setDepth($level);
}
