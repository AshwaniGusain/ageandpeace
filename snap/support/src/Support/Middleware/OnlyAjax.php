<?php

namespace Snap\Support\Middleware;

use Closure;

// http://stackoverflow.com/questions/32584700/how-to-prevent-laravel-routes-from-being-accessed-directly-i-e-non-ajax-request
class OnlyAjax
{
	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next)
	{
		if ( ! $request->ajax())
			return response('Forbidden.', 403);

		return $next($request);
	}
}