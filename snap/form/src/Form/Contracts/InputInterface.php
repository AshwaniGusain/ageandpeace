<?php

namespace Snap\Form\Contracts;

interface InputInterface
{
	public function setValue($value);
	public function getValue();
		
	public function setScope($scope);
	public function getScope();

	// // public function setValidation($validation);
	// // public function getValidation();

	// public function __toString();

}