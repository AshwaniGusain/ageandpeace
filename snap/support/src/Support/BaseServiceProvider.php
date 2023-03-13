<?php namespace Snap\Support;

use Event;
use Closure;

use Snap\Support\Interpolator;
use Snap\Support\Helpers\ArrayHelper;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

abstract class BaseServiceProvider extends ServiceProvider {

	protected $defer = true;
	
	protected $baseDir = __DIR__;
	protected $package = null;
	protected $namespace = null;
	protected $interpolator;

	protected $aliases = [];  // could be array or a string value that's a path
	protected $binders = [];
	protected $constants = null;
	protected $config = null;
	protected $events = [];
	protected $facades = [];  // could be array or a string value that's a path
	protected $helpers = [];
	protected $lang = [];
	protected $policies = [];
	protected $publish = [];
	protected $provides = [];
	protected $providers = [];  // could be array or a string value that's a path
	protected $routes =  null;
	protected $views =  null;
	protected $macros = [];
	protected $commands = [];

	public function __construct($app)
	{
		$this->app = $app;
		$this->interpolator = $this->app->make('Snap\Support\Interpolator');
	}

	/**
	* Register the service.
	*/
	public function register()
	{
		
		$this->initInterpolations();
		
		if ( ! empty($this->constants)) $this->constants($this->constants);
		if ( ! empty($this->helpers)) $this->helpers($this->helpers);
		if ( ! empty($this->config)) $this->config($this->config);
		if ( ! empty($this->binders)) $this->bind($this->binders, 'bind');
		if ( ! empty($this->aliases)) $this->bind($this->aliases, 'alias');
		if ( ! empty($this->facades)) $this->facades($this->facades);
		if ( ! empty($this->macros)) $this->macros($this->macros);
		if ( ! empty($this->providers)) $this->providers($this->providers);
		if ( ! empty($this->views)) $this->views($this->views);
		if ( ! empty($this->commands)) $this->commands($this->commands);

	}

	/**
	 * Bootstrap any application services.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot()
	{
		if (empty($this->baseDir)) {
			$this->baseDir = __DIR__;
		}

		if ( ! empty($this->routes)) {
			$this->setRootControllerNamespace();

			if ($this->app->routesAreCached()) {
				$this->loadCachedRoutes();
			} else {
				$this->loadRoutes();
			}
		}

		if ( ! empty($this->lang)) $this->lang($this->lang);
		if ( ! empty($this->policies)) $this->policies($this->policies);
		if ( ! empty($this->events)) $this->events($this->events);
		if ( ! empty($this->publish)) $this->publish($this->publish);
	}

	public function provides()
	{
		if ( ! empty($this->provides)) {
			return (array) $this->provides;
		}
		return [];
	}

	/**
	 * Set the root controller namespace for the application.
	 *
	 * @return void
	 */
	protected function setRootControllerNamespace()
	{
		if (is_null($this->namespace)) return;

		$this->app['Illuminate\Contracts\Routing\UrlGenerator']
						->setRootControllerNamespace($this->namespace);
	}

	/**
	 * Load the cached routes for the application.
	 *
	 * @return void
	 */
	protected function loadCachedRoutes()
	{
		$this->app->booted(function()
		{
			require $this->app->getCachedRoutesPath();
		});
	}

	/**
	 * Load the application routes.
	 *
	 * @return void
	 */
	protected function loadRoutes()
	{
		if (! $this->app->routesAreCached())
		{
			$this->app->call([$this, 'routes'], [$this->routes]);
		}
	}

	/**
	 * Load the standard routes file for the application.
	 *
	 * @param  string  $path
	 * @return void
	 */
	protected function loadRoutesFrom($path)
	{
		$router = $this->app['Illuminate\Routing\Router'];

		if (is_null($this->namespace)) return require $path;

		$router->group(['namespace' => $this->namespace.'\Http\Controllers'], function($router) use ($path){
			require $path;
		});
	}

	protected function loadPolicies()
	{
		$this->app->call([$this, 'policies']);
	}

	public function config($config)
	{
		if (is_string($config)) {
			$path = $this->path($config);
			if (file_exists($path)) {
				$this->mergeConfigFrom($path, $this->package);
			}

		} elseif (is_array($config)) {
			foreach($config as $key => $path) {
				if (is_int($key)) {
					$key = $this->package;
				}

				$path = $this->path($path);
				$package = explode('::', $key);
				if (count($package) <= 1) {
					$key = $this->package.'::'.$key;
				}

				if (file_exists($path))	{
					$this->mergeConfigFrom($path, $key);
				}
			}
		}
	}

	public function lang($lang)
	{
		ArrayHelper::normalize($lang);

		foreach($lang as $key => $path) {
			if (is_int($key)) {
				$key = $this->package;
			}
			$this->loadTranslationsFrom($this->path($path), $key);
		}
	}

	public function providers($providers)
	{
		$providers = ArrayHelper::normalize($providers);

		foreach($providers as $provider) {
			$this->app->register($provider);
		}
	}

	public function bind($bind, $type = 'bind')
	{
		$bind = ArrayHelper::normalize($bind);
		foreach($bind as $key => $val) {
			if (is_int($key)) {
				$this->app->$type($this->interpolator->interpolate($val));	
			} else {
				$this->app->$type($key, $this->interpolator->interpolate($val));
			}
		}
	}

	protected function constants($constants)
	{
		$this->load($constants);
	}

	public function routes($routes)
	{
		if ( ! empty($routes)) {
			$path = $this->path($routes);
			$this->loadRoutesFrom($path);	
		}
	}

	protected function facades($facades)
	{
		$facades = ArrayHelper::normalize($facades);

		$aliasLoader = \Illuminate\Foundation\AliasLoader::getInstance();
		foreach($facades as $key => $val) {
			$aliasLoader->alias($key, '\\'.ltrim($val, '/'));
		}
	}

	protected function helpers($helpers)
	{
		$this->load($helpers);
	}

	protected function views($views)
	{
		$viewPath = $this->path($views);
		$this->loadViewsFrom($viewPath, $this->package);
	}

	protected function events($events)
	{
		$events = ArrayHelper::normalize($events);

		foreach($events as $event => $listener) {
			Event::listen($event, $listener);
		}
	}

	protected function publish($publish)
	{
		if ( ! empty($publish)) {
			foreach($publish as $tag => $paths) {
				$p = [];
				foreach($paths as $from => $to) {
					$p[$this->path($from)] = $this->path($to);
					$this->publishes($p, $tag);
				}
			}
		}
	}

	protected function macros($macros)
	{
		$macros = ArrayHelper::normalize($macros);
		foreach($macros as $file) {
			$path = $this->path($file);

			foreach (glob($path) as $filename) { 
				require_once($filename);
			}
		}
	}

	protected function policies($policies, GateContract $gate)
	{
		$policies = ArrayHelper::normalize($policies);

		foreach ($policies as $key => $value) {
			$gate->policy($key, $value);
		}
	}

	// @TODO... meant for when things are run via command line
	protected function install()
	{
		
	}
	
	protected function load($path)
	{
		if (empty($path)) {
			return;
		}

		if (is_array($path)) {
			$loaded = [];
			foreach($path as $k => $v) {
				$this->load($v);
			}
		} else if (is_string($path)) {
			// swap out placeholder for the package
			return require $this->path($path);
		}
	}

	protected function path($path)
	{	
		// If the path doesn't start with a ':', then we will apply the baseDir to the beginning of the path automatically
		if (!preg_match('#^(:|/)#U', $path)) {
			$path = $this->baseDir.'/'.$path;
		}

		$path = preg_replace('#^\.\./#U', $this->baseDir.'/../', $path);
		
		$path = $this->interpolator->interpolate($path);

		return $path;
	}



	// protected function interpolate($path){
	// 	$find = array_keys($this->interpolations);
	// 	$replace = array_values($this->interpolations);
	// 	foreach($replace as $key => $val) {
	// 		if ($val instanceof Closure) {
	// 			$replace[$key] = call_user_func($val);
	// 		}
	// 	}
	// 	$path =  str_replace($find, $replace, $path);
	// 	return $path;
	// }

	protected function initInterpolations() {
		$interpolations = [
			':dir'           => $this->baseDir,
			':package'       => $this->package,
			':base_path'     => rtrim(base_path(), '/'),
			':resource_path' => rtrim(resource_path(), '/'),
			':config_path'   => rtrim(config_path(), '/'),
			':view_path'     => rtrim($this->app['config']['view.paths'][0], '/'),
			':database_path' => rtrim(database_path(), '/'),
			':app_path'      => rtrim(app_path(), '/'),
			':public_path'   => rtrim(public_path(), '/'),
			':storage_path'  => rtrim(storage_path(), '/'),
			':config\((.+)\)'    => function($key){
				return config($key);
			},
		];

		$this->interpolator->add($interpolations);
	}

}
