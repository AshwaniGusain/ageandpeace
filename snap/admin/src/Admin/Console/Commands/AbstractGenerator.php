<?php

namespace Snap\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractGenerator extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Support\Composer $composer
     */
    public function __construct(Filesystem $files,  Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    abstract protected function buildClass($name);

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @return string
     */
    abstract protected function getNamespace();

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    abstract protected function replaceClass($stub, $name);


    /**
     * Gets the class name.
     * @param $name
     * @return string
     */
    abstract protected function getClassName($name);

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    abstract protected function getFilePath($name);

    /**
     * Execute the console command.
     *
     * @param bool $dump
     * @return bool|null
     */
    public function handle($dump = true)
    {
        $name = $this->getNameInput();

        $path = $this->getFilePath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($name)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        if ($dump) {
            $this->composer->dumpAutoloads();
        }

        $this->info($this->type.' created successfully.');

    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($name)
    {
        return $this->files->exists($this->getFilePath($name));
    }

    /**
     * Gets the model name upper-cased word style.
     *
     * @param $name
     * @return string
     */
    protected function getStudlyName($name)
    {
        return studly_case($name);
    }

    /**
     * Get plural name.
     *
     * @return string
     */
    protected function getPluralName($name)
    {
        return str_plural($this->getNameInput('name'), 2);
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['{{Namespace}}'],
            [$this->getNamespace($name)],
            $stub
        );

        return $this;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the desired class path option from the input.
     *
     * @return string
     */
    protected function getPathOption()
    {
        return $this->option('path');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the module'],
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/'.$this->type.'.stub';
    }

    /**
     * Returns a boolean value for an option.
     *
     * @param $option
     * @return bool
     */
    public function isTrueOption($option)
    {
        return ($this->hasOption('all') && $this->option('all')) ||
            ($this->hasOption($option) && ($this->option($option)));
    }
}
