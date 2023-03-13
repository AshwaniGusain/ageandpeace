<?php

namespace Snap\Page\Templates;

interface TemplateInteface
{
    public function initialize();

    public function handle();

    public function name();

    public function getPrefix();

    public function getForm();

    public function getRules();

    public function process($request);

    public function with($data);

    public function inputs();

    public function ui();

    public function render();

    public function toArray();

    public function jsonSerialize();

}