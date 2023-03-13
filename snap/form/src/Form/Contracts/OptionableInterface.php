<?php

namespace Snap\Form\Contracts;

interface OptionableInterface
{
	public function getOptions();
	public function setOptions($options);
}
