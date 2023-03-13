<?php

namespace Snap\Form\Contracts;

interface CheckableInterface
{
	// public function isCheckable();
	public function checkIfMatchValue($value);

}
