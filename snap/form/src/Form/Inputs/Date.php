<?php

namespace Snap\Form\Inputs;

use Carbon\Carbon;
use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\Traits\VueTrait;

class Date extends BaseInput
{
    use VueTrait;
    use AttrsTrait;
    use CssClassesTrait;

    //protected $inputType = 'date';  // Creates warning in console with incorrect format
    protected $inputType = 'text';  // Creates warning in console with incorrect format
    protected $vue = 'snap-date-input';
    protected $view = 'form::inputs.input';
    protected $scripts = [
        'assets/snap/js/components/form/DateInput.js',
        'assets/snap/vendor/moment/moment.min.js',
        'assets/snap/vendor/bootstrap/daterangepicker.js',
    ];
    protected $styles = [
        'assets/snap/vendor/bootstrap/daterangepicker.css',
    ];
    protected $options = [];
    protected $format = 'm/d/Y';

    //protected $data = [
    //
    //    'attrs' => [
    //        ':config' => ['local' => ['format' => 'MM/DD/YYYY']],
    //    ]
    //];


    protected function _render()
    {
        $this->options['locale']['format'] = $this->translateToJsDateFormat($this->format);

        $this->setAttrs([
            'name'  => $this->getName(),
            'value' => is_null($this->getValue()) ? null : (string) $this->getValue(),
            'id'    => $this->getId(),
            'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class') : 'form-control',
            'placeholder'  => $this->getPlaceholder(),
            ':options' => $this->getOptions(),
        ]);

        $this->with(['displayValue' => $this->getDisplayValue()]);

        $this->convertAttrsToJson([':options', 'is', 'value', 'name']);

        return parent::_render();
    }

    public function setValue($value)
    {
        if ($value instanceof Carbon || (! $value instanceof Carbon && (bool)strtotime($value))) {
            $this->value = Carbon::parse($value)->format($this->format);
        }

        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->setAttr('placeholder', $this->translateToJsDateFormat($placeholder));

        return $this;
    }

    public function getPlaceholder()
    {
        return $this->getAttr('placeholder');
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setOption($key, $val)
    {
        $this->options[$key] = $val;

        return $this;
    }

    public function getOption($key)
    {
        return $this->options[$key] ?? [];
    }

    public function setOptions($options)
    {
        if (is_string($options)) {
            $options = json_decode($options);
        }

        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        return $this->options ?? [];
    }

    protected function translateToJsDateFormat($format)
    {
        // order counts here!
        $format = str_replace('m', 'MM', $format);
        $format = str_replace('n', 'M', $format);
        $format = str_replace('d', 'DD', $format);
        $format = str_replace('j', 'D', $format);
        $format = str_replace('y', 'YY', $format);
        $format = str_replace('Y', 'YYYY', $format);
        $format = str_replace('H', 'h', $format);
        $format = str_replace('i', 'mm', $format);
        $format = str_replace('a', 'A', $format);

        return $format;
    }
}