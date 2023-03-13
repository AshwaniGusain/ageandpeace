<?php

namespace Snap\Menu;

interface ItemInterface
{
    public function __construct($id, array $props = []);

    public function setId($id);

    public function getId();

    public function setLabel($label);

    public function getLabel();

    public function setLink($link);

    public function getLink();

    public function setParent($parent);

    public function getParent();
}
