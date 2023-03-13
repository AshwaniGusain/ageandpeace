<?php

namespace Snap\Admin\Http\Controllers;

use Illuminate\Http\Response;

class DashboardController extends ModuleController
{
    public function index()
    {
        return $this->module->ui('dashboard', function ($ui) {

            /* Put your custom code table view code here:
            $ui->heading->setTitle('This is a new heading!');
            $ui->table
                ->setColumns(['name', 'email'])
                ->addIgnored('updated_at')
                ->addAction('{id}/show', 'SHOW')
                ->addAction(function($values){
                    return '<a href="'.$values['id'].'/delete">DELETE</a>';
                });
            */
        })->render();
    }

    public function ajax($dashboard)
    {
        foreach ($this->module->dashboards() as $widget) {
            $widget = new $widget();
            if ($widget->uri() == $dashboard) {
                return $widget;
            }
        }
        $json = ['success' => false, 'errors' => 'Invalid dashboard specified.'];
        return response($json, Response::HTTP_BAD_REQUEST);
    }

}
