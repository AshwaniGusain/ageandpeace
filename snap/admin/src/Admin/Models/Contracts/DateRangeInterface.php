<?php

namespace Snap\Admin\Models\Contracts;

interface DateRangeInterface
{
	public function getStartDateColumn();

	public function getEndDateColumn();
}