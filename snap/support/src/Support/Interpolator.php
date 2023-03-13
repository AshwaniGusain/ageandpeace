<?php

namespace Snap\Support;

class Interpolator
{
	public $paths = [];
	
	const HINT_DELIMITER = ':';

	public function __construct(array $paths = [])
	{
		$this->paths = $paths;
	}

	public function add($hint, $path = null, $index = null)
	{
		if (is_array($hint)) {
			foreach ($hint as $h => $p) {
				$this->add($h, $p);
			}
		} else {
			if (is_null($index)) {
				$index = count($this->paths);
			}

			$this->paths[$index] = [$hint => $path];
		}

		return $this;
	}

	public function translate($path)
	{
		$delimiter = self::HINT_DELIMITER;

		if (strpos($path, $delimiter) !== FALSE) {
			foreach($this->paths as $index => $val) {
				$token = key($val);
				$replace = current($val);

				if (is_string($replace)) {
					$path = str_replace($delimiter . $token, rtrim($replace, '/'), $path);	
				}
			}
		}

		return $path;
	}

	public function path($hint)
	{
		if (isset($this->paths[$hint])) {

			return $this->paths[$hint];	
		}
	}
}