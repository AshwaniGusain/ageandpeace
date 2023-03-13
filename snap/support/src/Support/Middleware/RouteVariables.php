<?php

namespace Snap\Support\Middleware;

use View;
use Closure;
use Illuminate\Support\Collection;

class RouteVariables
{
	/**
	 * Run the request filter.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$route = $request->route();
		if ( ! $route) return;
		$action = $route->getAction();
		if (isset($action['vars'])) {
			$vars = [];
			if (is_array($action['vars'])) {
				$vars = $action['vars'];
			} elseif ($action['vars'] instanceof Collection) {
				$vars = $action['vars']->toArray();
			} elseif (is_string($action['vars']) && (strpos($action['vars'], '::') !== false || strpos($action['vars'], '.') !== false)) {
				$vars = config($action['vars'], []);
			}
			
			View::share($vars);
		}

		return $next($request);
	}

}