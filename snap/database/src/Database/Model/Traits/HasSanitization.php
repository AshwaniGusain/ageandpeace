<?php

namespace Snap\Database\Model\Traits;

use Snap\Support\Sanitizer;
use Snap\Database\Model\Traits\Observables\SanitizationObserver;

trait HasSanitization {

	 /**
	 * Boot the trait. Adds an observer class for validating.
	 *
	 * @return void
	 */
	public static function bootHasSanitization()
	{
		static::observe(new SanitizationObserver);
	}

    /**
     * Runs the sanitization rules on the model (called on save).
     *
     * @param $values
     */
    public function sanitize($values)
	{
		$sanitizer = new Sanitizer($this->sanitization);
		$values = $sanitizer->sanitize($this->attributes);
		foreach($values as $key => $val) {
			$this->attributes[$key] = $val;
		}
	}

    /**
     * Determines whether
     *
     * @return bool
     */
    public function hasSanitization()
    {
    	return !empty($this->sanitization);
    }
}