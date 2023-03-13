<?php

namespace Snap\Docs;

use League\CommonMark\Environment;
use League\CommonMark\ConverterInterface;
use Snap\Docs\Inspector\Inspector;

/*
 * https://github.com/GrahamCampbell/Laravel-Markdown
 * */
class DocsManager
{
    /**
     * @var array
     */
    protected $sections = [];

    /**
     * @var array
     */
    protected $packages = [];

    /**
     * @var string
     */
    protected $defaultSection = 'App';

    /**
     * @var
     */
    protected $url;

    /**
     * @var \Snap\Docs\FuncReplacer
     */
    protected $funcReplacer;

    /**
     * @var \League\CommonMark\Environment
     */
    protected $environment;

    /**
     * @var \League\CommonMark\ConverterInterface
     */
    protected $converter;

    /**
     * DocsManager constructor.
     *
     * @param $config
     * @param \League\CommonMark\ConverterInterface $converter
     * @param \League\CommonMark\Environment $environment
     * @param \Snap\Docs\FuncReplacer $funcReplacer
     */
    public function __construct($config, ConverterInterface $converter, Environment $environment, FuncReplacer $funcReplacer)
    {
        $this->initialize($config);
        $this->converter = $converter;
        $this->environment = $environment;
        $this->funcReplacer = $funcReplacer;
    }

    /**
     * @param $config
     */
    protected function initialize($config)
    {
        if (is_array($config['toc'])) {
            foreach ($config['toc'] as $section => $packages) {
                foreach ($packages as $name => $label) {
                    if ($path = $this->getPackageDocsPath($name)) {
                        $this->add($name, $path, $label, $section);
                    }
                }
            }
        } else {
            $this->autoFind();
        }

        // Set the URL that is used to replace the {docs_url} placeholder.
        if (isset($config['url'])) {
            $this->url = $config['url'];
        } elseif (\Admin::modules()->has('docs')) {
            $this->url = \Admin::modules('docs')->url();
        }
    }

    protected function autoFind()
    {
        $appDocsPath = base_path().'/docs/index.md';
        if (file_exists($appDocsPath)) {
            $this->add('app', $appDocsPath, 'My Docs', 'Project');
        }

        foreach (glob(snap_path()."*/") as $path) {
            if (file_exists($path.'/docs/index.md')) {
                $name = pathinfo($path, PATHINFO_BASENAME);
                $label = ucfirst($name);
                $section = trans('docs::messages.snap');
                $this->add($name, $path, $label, $section);
            }
        }
    }

    /**
     * @param $name
     * @return bool|string
     */
    protected function getPackageDocsPath($name)
    {
        if ($name == 'app') {
            $path = base_path().'/docs';
        } else {
            $path = base_path().'/snap/'.$name.'/docs';
        }

        if (is_dir($path)) {
            return $path;
        }

        return false;
    }

    /**
     * @param $handle
     * @param $dir
     * @param null $label
     * @param null $section
     * @return $this
     */
    public function add($handle, $dir, $label = null, $section = null)
    {
        if (empty($section)) {
            $section = $this->defaultSection;
        }

        if (empty($label)) {
            $label = ucfirst($handle);
        }

        $package = new DocsPackage($handle, $label, $this->getPackageDocsPath($handle), $section);

        $this->packages[$handle] =  $package;
        $this->sections[$section][$handle] =  $package;

        return $this;
    }

    /**
     * @return array
     */
    public function sections()
    {
        return $this->sections;
    }

    /**
     * @param $section
     * @return mixed|null
     */
    public function section($section)
    {
        if (isset($this->sections[$section])) {
            return $this->sections[$section];
        }

        return null;
    }

    /**
     * @return array
     */
    public function packages()
    {
        return $this->packages;
    }

    /**
     * @param $handle
     * @return mixed|null
     */
    public function package($handle)
    {
        if (isset($this->packages[$handle])) {
            return $this->packages[$handle];
        }

        return null;
    }

    /**
     * @param $package
     * @return bool
     */
    public function hasDocs($package)
    {
        foreach ($this->sections as $section => $packages) {
            foreach ($packages as $p) {
                if (strtolower($package) == strtolower($p)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $uri
     * @return string
     */
    public function url($uri = '')
    {
        return $uri ? trim($this->url, '/') . '/'.$uri : $this->url;
    }

    /**
     * @param $path
     * @return bool|mixed|null|string|string[]
     */
    protected function getHtml($path)
    {
        $html = file_get_contents($path);
        $html = $this->replacePlaceholders($html);
        $html = $this->autoLink($html);

        return $html;
    }

    /**
     * @param $html
     * @return null|string|string[]
     */
    public function autoLink($html)
    {
        $html = preg_replace_callback('#`([\w\d\\\:]+)`#U', function($matches){
            $classPath = $matches[1];
            $classParts = explode('::', $classPath);
            $class = $classParts[0];
            $method = !empty($classParts[1]) ? '#method-' . $classParts[1] : '';
            if (strpos($class, '\\') !== false && (Inspector::isInspectable($class)) && (new Inspector($class))->canAutoDoc()) {
                return '<a href="'.$this->url.'/auto?c='.$class.$method.'" class="docs-link"><code>'.$classPath.'</code></a>';
            }
            return $matches[0];
        }, $html);

        return $html;
    }

    /**
     * @param $html
     * @return mixed
     */
    protected function replacePlaceholders($html)
    {
        $html = str_replace('{docs_url}', $this->url, $html);
        $html = $this->funcReplacer->setHtml($html)->run();

        return $html;
    }

    /**
     * @param $package
     * @param $page
     * @return bool|mixed|null|string|string[]
     */
    public function page($package, $page)
    {
        if (isset($this->packages[$package])) {
            $path = rtrim($this->packages[$package]->path, '/').'/'.$page.'.md';

            if (file_exists($path)) {

                $html = $this->getHtml($path);
                $this->environment->mergeConfig(['html_input' => 'allow']);
                $html = $this->converter->convertToHtml($html);

                return $html;
            }
        }

        abort(404);

        return '';
    }

    /**
     * @param $class
     * @return \Snap\Docs\Inspector\Inspector
     */
    public function classDoc($class)
    {
        return new Inspector($class);
    }

    /**
     * @return \Snap\Docs\FuncReplacer
     */
    public function funcs(): FuncReplacer
    {
        return $this->funcReplacer;
    }

}