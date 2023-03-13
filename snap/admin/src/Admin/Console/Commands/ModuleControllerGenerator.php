<?php

namespace Snap\Admin\Console\Commands;

class ModuleControllerGenerator extends AbstractGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:controller {name : The name of the controller.}
        {--resource : Will create a resource module controller instead of a normal module controller.}
        {--path= : The location where the controller file should be created.}
        {--force : Replaces a file if it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a controller for a SNAP module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                    ->replaceClass($stub, $name);
    }

    /**
     * Gets the class name.
     *
     * @param $name
     * @return string
     */
    protected function getClassName($name)
    {
        return $this->getStudlyName($name).'Controller';
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

        return admin_path("Controllers/".$this->getStudlyName($name)."Controller.php");
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return config('snap.admin.route.namespace').'\Controllers';
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
        return str_replace('{{ControllerClass}}', $this->getStudlyName($name), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->isTrueOption('resource')) {
            return __DIR__.'/stubs/ResourceModuleController.stub';
        } else {
            return __DIR__.'/stubs/ModuleController.stub';
        }
    }
}
