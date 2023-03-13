<?php 

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\UiComponent;
use Snap\Ui\Traits\CssClassesTrait;

class ButtonGroup extends UiComponent {

    use CssClassesTrait;

    protected $view = 'component::bootstrap.button-group';
    protected $data = [
        'object:buttons' => 'Illuminate\Support\Collection',
        'active' => null,
        'attrs' => [],
    ];

    public function add($button, $params = [], $key = null)
    {
        if (is_array($button)) {

            foreach($button as $key => $val) {
                $this->add($key, $val);
            }

        } else {

            if (empty($key)) {
                $key = count($this->buttons);
            }

            if (is_string($button)) {
                
                if (is_string($params)) {
                    $params = ['attrs' => ['href' => $params]];
                }

                $params['label'] = $button;
                $defaultAttrs = ['class' => 'btn'];
                $params['attrs'] = (isset($params['attrs'])) ? array_merge($defaultAttrs, $params['attrs']) : $defaultAttrs;

                if (isset($params['attrs']['href'])) {
                    $button = new ButtonLink($params);
                } else {
                    $button = new Button($params);
                }
            }

            $this->data['buttons'][$key] = $button;
        }

        return $this;
    }

    public function setClass($class)
    {
        $class = array_normalize($class);

        if (!in_array('btn-group', $class)) {
            $class[] = 'btn-group';
        }

        $this->data['class'] = $class;
        
        return $this;
    }

    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    protected function _render()
    {
        if (isset($this->buttons[$this->active])) {
            $this->buttons[$this->active]->addClass('active');
        }

        return parent::_render();
    }

}