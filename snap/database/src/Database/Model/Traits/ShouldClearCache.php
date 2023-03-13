<?php

namespace Snap\Database\Model\Traits;

use Cache;
use Snap\Database\Model\Traits\Observables\ShouldClearCacheObserver;

trait ShouldClearCache {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootShouldClearCache()
    {
        static::observe(new ShouldClearCacheObserver());
    }

    /**
     * Clears the cache. Can be overwritten
     *
     * @return bool
     */
    public function clearCache()
    {
        Cache::flush();
        return true;
    }
}
