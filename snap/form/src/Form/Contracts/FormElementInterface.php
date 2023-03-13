<?php

namespace Snap\Form\Contracts;

interface FormElementInterface
{
	public function setKey($key);
	public function getKey();
	public function setOrder($order);
	public function getOrder();
	public function setGroup($group);
	public function getGroup();
	public function getType();
	public function render();
	public function __toString();
}