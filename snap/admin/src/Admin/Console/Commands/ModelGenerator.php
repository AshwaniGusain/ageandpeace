<?php

namespace Snap\Admin\Console\Commands;

class ModelGenerator extends AbstractGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:model {name : The name of the model.}
        {--migration : Will create a migration file for the model.}
        {--path= : The location where the model file should be created.}
        {--force : Replaces a file if it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a model for a SNAP module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Execute the console command.
     *
     * @param bool $dump
     * @return void
     */
    public function handle($dump = true)
    {
        parent::handle($dump);

        $name = $this->getNameInput();

        if ($this->isTrueOption('migration')) {
            $this->createMigration($name);
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Gets the class name.
     *
     * @param $name
     * @return string
     */
    protected function getClassName($name)
    {
        return $this->getStudlyName($name);
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

        return app_path("Models/".$this->getStudlyName($name).".php");
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return $this->laravel->getNamespace()."Models";
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
        return str_replace('{{ModelClass}}', $this->getStudlyName($name), $stub);
    }

    /**
     * Create model migration.
     *
     * @param $name
     */
    protected function createMigration($name)
    {
        $table = snake_case($this->getPluralName($name));

        $this->call('make:migration', [
            'name' => 'create_'.$table.'_table',
            '--create' => $table,
            '--table' => $table,
        ]);
    }
}
