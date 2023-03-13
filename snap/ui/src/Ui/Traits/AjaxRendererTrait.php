<?php

namespace Snap\Ui\Traits;

use Request;

trait AjaxRendererTrait
{
    public function bootAjaxRendererTrait()
    {
        $isAjax = Request::ajax() && ! Request::input('noajax', false);

        $this->addRenderer($isAjax, '_renderAjax');

        $this->data['is_ajax'] = $isAjax;
    }

    public function _renderAjax()
    {
        if (! empty($this->ajaxView)) {
            $this->setView($this->ajaxView);
        }

        return $this->_render();
    }
}