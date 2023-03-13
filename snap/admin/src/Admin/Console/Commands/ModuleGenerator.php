<?php

namespace Snap\Admin\Console\Commands;

use Illuminate\Support\Str;

class ModuleGenerator extends AbstractGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {name : The name of the module.}
        {--resource : Will create a resource module (which will need a model) instead of a normal module (which does not).}
        {--migration : Will create a migration file for the model associated with the module.}
        {--model : Will create an associated model with the module (for resource modules).}
        {--controller : Will create an associated controller for the module.}
        {--path= : The location where the module file should be created.}
        {--all : A shortcut to create a model and a controller both.}
        {--force : Replaces a file if it already exists.}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a SNAP module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Module';

    /**
     * Execute the console command.
     *
     * @param bool $dump
     * @return void
     */
    public function handle($dump = true)
    {
        parent::handle(false);

        $name = $this->getNameInput();

        if ($this->isTrueOption('controller')) {
            $this->createController($name);
        }

        if ($this->isTrueOption('model')) {
            $this->createModel($name);
        }

        //$this->composer->dumpAutoloads();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceModuleName($stub, $name)
                    ->replaceModelClass($stub, $name)
                    ->replaceController($stub, $name)
                    ->replaceMigration($stub, $name)
                    ->replaceNamespace($stub, $name)
                    ->replaceClass($stub, $name)
            ;
    }

    /**
     * Gets the class name.
     * @param $name
     * @return string
     */
    protected function getClassName($name)
    {
        return $this->getStudlyName($name).'Module';
    }

    /**
     * Gets the friendly name for the module.
     * @param $name
     * @return string
     */
    protected function getFriendlyName($name)
    {
        return ucwords(str_replace(['_'], [' '], $name));
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getFilePath($name)
    {
        if ($option = $this->getPathOption()) {
            return $option;
        }

        return admin_path("Modules/".$this->getStudlyName($name)."Module.php");
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return config('snap.admin.route.namespace').'\Modules';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        return str_replace('{{ModuleClass}}', $this->getStudlyName($name).'Module', $stub);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceModuleName(&$stub, $name)
    {
        $find = ['{{module}}', '{{Modules}}', '{{Module}}'];
        $replace = [$name, str_plural($this->getFriendlyName($name)), $this->getFriendlyName($name)];

        $stub = str_replace($find, $replace, $stub);

        return $this;
    }

    /**
     * Replace the model class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceModelClass(&$stub, $name)
    {
        $find = ['{{ModelClass}}'];

        $replace = [$this->getStudlyName($name)];

        $stub = str_replace($find, $replace, $stub);

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceController(&$stub, $name)
    {
        $find = ['{{ModuleController}}'];
        $replace = [$this->getControllerOption()];

        $stub = str_replace($find, $replace, $stub);

        return $this;
    }

    /**
     * Returns the controller class to be used with the module.
     *
     * @return array|string
     */
    protected function getControllerOption()
    {
        $controller = $this->isTrueOption('controller');

        if ($controller) {
            $name = $this->getStudlyName($this->getNameInput());
            $controller = config('snap.admin.route.namespace').'\Controllers\\'.$name.'Controller';
        } else {
            $controller = 'Snap\Admin\Http\Controllers\ResourceModuleController';
        }

        return $controller;
    }

    /**
     * Replace the migration class for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceMigration(&$stub, $name)
    {
        $find = ['{{ModuleMigration}}'];

        if ($this->isTrueOption('migration')) {
            $replace = [$this->getMigrationOption($name)];
        } else {
            $replace = '';
        }

        $stub = str_replace($find, $replace, $stub);

        return $this;
    }

    /**
     * Returns the migration class name to be used with the module.
     *
     * @return array|string
     */
    protected function getMigrationOption($name)
    {
        return studly_case('create_'.str_plural(Str::lower($name)).'_table');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->isTrueOption('resource')) {
            return __DIR__.'/stubs/ResourceModule.stub';
        } else {
            return __DIR__.'/stubs/Module.stub';
        }
    }

    protected function createModel($name)
    {
        // Generate model
        $params = [
            'name' => $this->getStudlyName($name),
            '--path' => $this->getPathOption($name),
        ];

        if ($this->isTrueOption('migration')) {
            $params['--migration'] = true;
        }

        $this->call('module:model', $params);
    }

    protected function createController($name)
    {
        // Generate migration
        $params = [
            'name' => $this->getStudlyName($name),
            '--path' => $this->getPathOption($name),
        ];

        if ($this->isTrueOption('resource')) {
            $params['--resource'] = true;
        }

        $this->call('module:controller', $params);
    }

}
