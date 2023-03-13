<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Blade::directive('render', function ($component) {
            return "<?php echo (app($component))->toHtml(); ?>";
        });

        \Blade::if('cached', function ($cacheKey) {
            return (\Cache::has($cacheKey));
        });

        \Blade::directive('cachebegin', function ($cacheKey) {
            return '<?php if (\Cache::has("'.$cacheKey.'")) : 
                echo \Cache::get("'.$cacheKey.'");
                else:
                capture();
            ?>';

        });

        \Blade::directive('cacheend', function ($cacheKey, $limit = 0) {
            if ($limit) {
                return '<?php echo cache_captured("'.$cacheKey.'", '.$limit.'); endif; ?>';
            }
            return '<?php echo cache_captured("'.$cacheKey.'"); endif; ?>';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}