<?php 

namespace Snap\Ui\Components\Bootstrap;

use Illuminate\Support\ViewErrorBag;
use Session;
use Illuminate\Support\MessageBag;
use Snap\Ui\UiComponent;


class Alerts extends UiComponent {

    protected $view = 'component::bootstrap.alerts';
    protected $data = [
        //'object:alerts' => 'Illuminate\Support\Collection',
        'alerts' => [],
    ];

    public function initialize()
    {
        // Automatically any errors found in the flash message.
        if ($errors = bag(Session::get('errors'))) {
            //$this->add($errors->all(), 'danger');
        }

        if ($success = bag(Session::get('success'))) {
            $this->add($success->all(), 'success');
        }

        if ($info = bag(Session::get('info'))) {
            $this->add($info->all(), 'info');
        }

        if ($warning = bag(Session::get('warning'))) {
            $this->add($warning->all(), 'info');
        }
    }

    public function add($alert, $type = 'info', $refId = null, $dismissable = false)
    {
        if ($alert instanceof ViewErrorBag) {
            foreach ($alert->messages() as $refId => $messages) {
                foreach ($messages as $message) {
                    $this->add($message, $type, $refId);
                }
            }
        }
        elseif (is_iterable($alert)) {
            foreach ($alert as $val) {
                $this->add($val, $type);
            }
        } else {
            $alert = (new Alert())
                ->setText($alert)
                ->setType($type)
                ->setRefId($refId)
                ->setDismissable($dismissable)
                ;

            $this->data['alerts'][$type][] = $alert;
        }

        return $this;
    }

    public function addError($alert, $refId = null, $dismissable = false)
    {
        return $this->add($alert, 'error', $refId, $dismissable);
    }

    public function addSuccess($alert, $refId = null, $dismissable = false)
    {
        return $this->add($alert, 'success', $refId, $dismissable);
    }

    public function addInfo($alert, $refId = null, $dismissable = false)
    {
        return $this->add($alert, 'info', $refId, $dismissable);
    }

    public function addWarning($alert, $refId = null, $dismissable = false)
    {
        return $this->add($alert, 'warning', $refId, $dismissable);
    }

    public function get($type = null)
    {
        if ($type) {
            if (isset($this->data['alerts'][$type])) {
                return $this->data['alerts'][$type];    
            }
        }

        return $this->data['alerts'];
    }

}