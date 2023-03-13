<?php

namespace Snap\Admin\Http\Middleware;

use Closure;
use ContentManager;

class ResourcePreview
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
        // @TODO put logic here to detect if the request is coming from the admin
        ContentManager::appendVariables($this->cleanedRequestVars($request));

        return $next($request);
    }

    protected function cleanedRequestVars($request)
    {
        $vars = [];
        $ignore = ['_method', '_token'];
        foreach($request->all() as $key => $val) {
            if ( ! in_array($key, $ignore)) {
                $vars[$key] = $val;
            }
        }

        return $vars;
    }
}