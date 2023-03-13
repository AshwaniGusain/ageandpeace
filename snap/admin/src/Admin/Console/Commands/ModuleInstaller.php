<?php

namespace Snap\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModuleInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs a SNAP module by running any database migrations, installing permissions and publishing any necessary files.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return null
     */
    public function handle()
    {
        $name = $this->argument('name');

        $resource = $this->option('resource');

        $this->generateFile($name, $resource);

        $this->createModel($name);
    }

    protected function generateFile($name, $resource)
    {
        $find = ['{{module}}', '{{Module}}', '{{Modules}}'];
        $replace = [$name, ucfirst($name), str_plural($name, 2)];
        $moduleTemplate = str_replace($find, $replace, $this->getStub('ResourceModule'));

        $this->files->put(admin_path("Modules/".ucfirst($name)."Module.php"), $moduleTemplate);

        $this->info('Module "'.$name.'" created successfully.');
    }

    protected function createModel($name)
    {
        // Generate migration
        $this->call('make:model', [
            'name' => ucfirst($name),
            '--migration' => true,
        ]);
    }


    protected function getStub($type)
    {
        $path = realpath(__DIR__.'/stubs/');

        return file_get_contents($path."/$type.stub");
    }
}
