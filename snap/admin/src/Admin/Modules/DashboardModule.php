<?php

namespace Snap\Admin\Modules;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Snap\Admin\Models\Log;
use Snap\DataTable\DataTable;
use Snap\Ui\Components\BulletList;
use Snap\Ui\Components\Bootstrap\Card;
use Snap\Ui\Components\Bootstrap\ListGroup;

class DashboardModule extends Module
{
    use \Snap\Admin\Modules\Traits\NavigableTrait;

    protected $parent = null;
    protected $handle = 'dashboard';
    protected $name = 'Dashboard';
    protected $pluralName = 'Dashboard';
    protected $menuParent = '';
    protected $menuLabel = 'Dashboard';
    protected $description = '';
    protected $version = '1.0.0';
    protected $icon = 'fa fa-dashboard';
    protected $path = __DIR__;
    protected $modules = [];
    protected $uri = null;
    protected $permissions = ['view dashboard'];
    protected $config = null;
    protected $routes = [];
    protected $controller = '\Snap\Admin\Http\Controllers\DashboardController';
    protected $ui = [
        'dashboard'     => 'module.dashboard',
    ];

    public function register()
    {
        parent::register();
        $this->addRoute(['get'], '{dashboard}', '@ajax', []);
    }

    public function dashboards()
    {
        $dashboards = [];
        foreach (array_flatten(config('snap.admin.dashboard', [])) as $dashboard) {
            $dashboards[$dashboard::uri()] = $dashboard;
        }

        return $dashboards;
    }

    public function uiDashboard($ui, Request $request)
    {

        //$logs = Log::limit(10)
        //   ->orderBy('updated_at', 'desc')
        //   ->get()
        //;
        //$list = new BulletList();
        //foreach ($logs as $log) {
        //    $list->add($log->level.': '.$log->message.' - '.$log->updated_at->format(config('snap.admin.date_format')));
        //}
        //
        //$card = new Card();
        //$card->setHeader('Stats')
        //    ->setBody($list)
        //    ->setFooter('This is the footer of the card')
        //;
        //$ui->grid
        //    ->add($card)
        //;
        //
        //$table = new DataTable();
        //$data[] = [
        //    'visits' => 100,
        //    'time spent' => '1mn',
        //];
        //$data[] = [
        //    'visits' => 200,
        //    'time spent' => '2mn',
        //];
        //$table->load($data);
        //$ui->grid->add($table);
        //
        //$list = new ListGroup();
        //$items = new Collection();
        //$items[] = '<a href="#">Post Name 1</a>';
        //$items[] = '<a href="#">Post Name 2</a>';
        //$list->add($items);
        //
        //$ui->grid->add($list);
    }

    public function routes()
    {
        parent::routes();

        foreach ($this->dashboards() as $dashboard) {
           // if ()
        }
    }
}