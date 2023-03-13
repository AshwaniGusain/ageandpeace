<?php

namespace Snap\Page\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Page\Modules\Services\PublicRoutesService;

trait PublicRoutesTrait
{
    public function registerPublicRoutesTrait()
    {
        $this->aliasTrait('publicRoutes', 'Snap\Admin\Modules\Traits\PublicRoutesTrait');
        $this->addRoute(['get'], 'loading', '@loading', ['as' => 'loading']);

        // Because the menu is needed for all modules, we register it here.
        $this->bindService('publicRoutes', function(){
            $service = PublicRoutesService::make($this);
            if (property_exists($this, 'publicBaseUri')) {
                $service->prefix($this->publicBaseUri);
            }
            return $service;
        }, 'publicRoutesService');

    }

    public function bootPublicRoutesTrait()
    {
        $this->addUiCallback(['create', 'edit'], function ($ui) {
            $service = $this->service('publicRoutes');
            $ui->preview
                ->setSlugInput($service->slugInput)
                ->setPrefix($service->prefix)
                ->setLoadingUrl($this->url('loading'))
                ->visible(true)
            ;
            $ui->heading->preview_button->visible(true);
            //$ui->form->get($service->slugInput)->setPrefix($service->prefix);
        }, 'initialized');
    }

    public function publicRoutesService(PublicRoutesService $publicUrls = null, Request $request = null)
    {

    }

    public function publicRoutes()
    {

    }
}