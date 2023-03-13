<?php

namespace Snap\Support;

use Illuminate\Support\Str;
use Snap\Support\Helpers\SanitizeHelper;

class Sanitizer {

	protected $sanitization;

	public function __construct($sanitization = null)
	{
		if ($sanitization) {
			$this->sanitization = $sanitization;	
		}
	}

	public function hasSanitization()
	{
		return !empty($this->sanitization);
	}

	public function addSanitizer($key, $rules)
	{
		$this->sanitization[$key] = $rules;

		return $this;
	}

	public function removeSanitizer($key)
	{
		unset($this->sanitization[$key]);

		return $this;
	}

	public function sanitize($values)
	{
		if (!empty($this->sanitization)) {
			
			foreach($this->sanitization as $attribute => $cleaners) {
			
				$cleaners = explode('|', $cleaners);

				foreach($cleaners as $cleaner) {

					$toClean = $this->normalizeAttributesToClean($attribute, $values);

					foreach($toClean as $attr) {

						$cleaner = str_replace('::', '__', $cleaner);
						$params = explode(':', $cleaner);
						$cleaner = array_shift($params);
						$cleaner = str_replace('__', '::', $cleaner);
						$func = $this->determineCallableSanitizationFunc($cleaner);

						if ($func && isset($values[$attr])) {
							$value = $values[$attr];	
							array_unshift($params, $value);
							if ( ! is_object($value)) {
                                $values[$attr] = call_user_func_array($func, $params);
                            }
						}
					}
				}
			}
		}

		return $values;
	}

	protected function determineCallableSanitizationFunc($cleaner)
	{
		$func = null;
		if (SanitizeHelper::hasMacro(Str::camel($cleaner))) {
			$func = 'Snap\Helper\SanitizeHelper::'.Str::camel($cleaner);
		
		} elseif (method_exists($this, $cleaner)) {
			$func = [$this, $cleaner];
		
		} elseif (is_callable($cleaner)) {
			$func = $cleaner;
		}

		return $func;
	}

	protected function normalizeAttributesToClean($attribute, $values)
	{
		// normalize
		if ($attribute == '*' && is_array($values)) {
			$toClean = array_keys($values);
		} else {
			$toClean = explode('|', $attribute);
		}
		
		return $toClean;
	}
}