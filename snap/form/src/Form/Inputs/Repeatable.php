<?php

namespace Snap\Form\Inputs;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Snap\Form\Contracts\InputInterface;
use Snap\Form\Contracts\NestableInterface;
use Snap\Form\Form;
use Snap\Form\FormElements;
use Snap\Ui\Traits\AjaxRendererTrait;
use Snap\Ui\Traits\VueTrait;

class Repeatable extends BaseInput implements NestableInterface, JsonSerializable, ArrayAccess, IteratorAggregate, Countable
{
    use VueTrait;
    use AjaxRendererTrait;

    protected $vue = 'snap-repeatable-input';

    protected $scripts = [
        //'assets/snap/vendor/sortable/Sortable.min.js',
        'assets/snap/vendor/jquery-ui/sortable.js',
        'assets/snap/js/components/form/RepeatableInput.js',
    ];

    protected $inputs;

    protected $rows = [];

    protected $context;

    protected $view = 'form::inputs.repeatable';

    protected $nested = false;

    protected $form;

    protected $data = [
        'min'       => null,
        'max'       => null,
        'sortable'  => true,
        'warn'      => 'Are you sure you want to remove this?',
        'template'  => 'repeatable-template',
        //'row_label' => 'ROW #{{ num }}: {{ values.subname }}',
        'row_label' => '',
        'row_view'  => null,
        'url'       => null,
        'collapse'  => false,
        'depth'     => 0,
    ];

    protected $cast = 'array';

    public function __construct($name, array $data = [])
    {
        //$this->form = resolve(Form::class);
        //$this->form->setTemplate('admin::components.form');
        $this->inputs = new FormElements();

        parent::__construct($name, $data);
    }

    //public function initialize()
    //{
    //    $this->setPostProcess(function($value, $input, $request){
    //        if (is_json($value)) {
    //            $request->request->set($this->key, json_encode($value));
    //        }
    //    });
    //}

    public function getInputs()
    {
        //foreach ($this->inputs as $field) {
        //    $field->setAttr('data-input-depth', $this->depth);
        //}

        return $this->inputs;
    }

    public function setInputs($inputs)
    {
        if (! $inputs instanceof FormElements) {
            $inputs = new FormElements($this->createInputs($inputs));
        }

        $this->inputs = $inputs;

        return $this;
    }

    public function createInputs($inputs = [])
    {
        //$fields = $this->processFieldNames($fields);
        foreach ($inputs as $key => $val) {
            if (! $val instanceof InputInterface) {
                //if (empty($val['label'])) {
                //    $val['label'] = humanize(preg_replace('#(.+)_id$#', '$1', $key));
                //}
                $input = \Form::element($key, $val);

                //$input = $this->form->create($key, $val);
                //$this->form->add($input);
            } else {
                $input = $val;
            }

            $input->setAttr('data-id', Form::createId($input->getName()));
            // $input->setAttr('data-name-template', $val['name']);
            $input->setAttr('data-key', $key);

            if ($input instanceof Repeatable) {
                $input->setDepth($this->depth + 1);
                $input->setNested(true);
            }

            //$input->setParent($this);
            $inputs[$key] = $input;
        }

        return $inputs;
    }

    public function getUrl()
    {
        $url = $this->data['url'];

        if (empty($url)) {
            $url = \Admin::modules()->current()->url().'/input';
        }

        return $url;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    public function getRow($i)
    {
        if (isset($this->rows[$i])) {
            return $this->rows;
        }

        return null;
    }

    public function isNested()
    {
        return $this->nested;
    }

    public function setNested($nested)
    {
        $this->nested = (bool) $nested;

        return $this;
    }

    //public function getValue()
    //{
    //    // $old = old($this->getKey());
    //
    //    // if ($old) return $old;
    //
    //    $value = parent::getValue();
    //
    //    if (is_json($value)) {
    //        $value = json_decode($value, true);
    //    }
    //
    //    if ($value) {
    //        $inputs = $this->getInputs();
    //
    //        foreach ($value as $index => $row) {
    //            foreach ($inputs as $key => $input) {
    //                if (isset($row[$input->getName()])) {
    //                    $input->setValue($value[$index][$input->getName()]);
    //
    //                    // Will run any pre-processing
    //                    $value[$index][$input->getName()] = $input->getValue();
    //
    //                    // Set it back to zilch so that it won't throw Vue compiled template errors if there is template code
    //                    // $field->setValue('');
    //                }
    //            }
    //        }
    //    }
    //
    //    return $value;
    //}

    //public function setValue($value)
    //{
    //    parent::setValue($value);
    //
    //    if (is_json($value)) {
    //        $value = json_decode($value, true);
    //    }
    //    if ($value) {
    //        foreach ($value as $index => $row) {
    //            //$inputs = $this->getInputs()->clone();
    //            $inputs = $this->getInputs();
    //            foreach ($inputs as $key => $input) {
    //
    //                if (isset($row[$input->getName()])) {
    //                    $input->setValue($value[$index][$input->getName()]);
    //
    //                    // Will run any pre-processing
    //                    $value[$index][$input->getName()] = $input->getValue();
    //
    //                    // Set it back to zilch so that it won't throw Vue compiled template errors if there is template code
    //                    // $field->setValue('');
    //                }
    //            }
    //        }
    //    }
    //
    //    return $this;
    //}

    public function createRows()
    {
        $this->rows = [];

        $values = $this->getValue();

        if (is_json($values)) {
            $values = json_decode($values, true);
        }

        $i = 0;

        // Now loop through the values to create rows
        if (is_array($values)) {
            foreach ($values as $i => $vals) {
                $this->createRow($i, $vals);
                $i++;
            }
        }

        // If the number of values is less then the min,
        // we'll add additional blank ones.
        if ($num = count($this->rows) < $this->min) {
            for ($j = 0; $j < ($this->min - $num); $j++) {
                $this->createRow($i);
                $i++;
            }
        }

        // If no values are set, then we create one row so they can see what to fill out
        if (! $values) {
            $this->createRow($i);
        }

    }

    protected function _render()
    {
        $this->createRows();

        $this->data['name'] = $this->getName();
        $this->data['rows'] = $this->getRows();

        $this->with([
            'name'    => $this->getName(),
            'url'     => $this->getUrl(),
            'inputs'  => $this->getInputs(),
            'numrows' => count($this->rows),
            'warn'    => htmlentities($this->warn),
            'input'   => str_replace(['[', '][', ']', '*'], ['.', '.', '', '.'], $this->getName()),
            //'scope' => str_replace(['[', ']', '*'], ['', '', '.'], $this->getName()),
        ]);

        return parent::_render();
    }

    // protected function replaceIndexValue($index, $element)
    // {
    // 	$newKey = str_replace('.*.', ".{$index}.", $element->getKey());
    // 	return $newKey;
    // }

    // Not needed because it is driven by Vue's values

    public function _renderAjax()
    {

        static $view;
        $this->createRows();
        $data = [
            'name'    => $this->getName(),
            'url'     => $this->getUrl(),
            'inputs'  => $this->getInputs(),
            'numrows' => count($this->getRows()),
            'warn'    => htmlentities($this->getWarn()),
            'input'   => str_replace(['[', ']', '*'], ['', '', '.'], $this->getName()),
            'rows'    => $this->getRows(),
            //'scope' => str_replace(['[', ']', '*'], ['', '', '.'], $this->getName()),
        ];
        $data = array_merge($this->data, $data);

        $view = ($view == 'form::inputs.repeatable-row') ? 'form::inputs.repeatable' : 'form::inputs.repeatable-row';

        return view($view, $data);
        //return $this->_render();
    }

    // Not needed because of casting
    //public function postProcess($value, Request $request = null)
    //{
    //    $values = [];
    //
    //    if (is_string($value)) {
    //        $value = json_decode($value, true);
    //    }
    //
    //    foreach ($value as $index => $v) {
    //
    //        foreach ($this->getInputs() as $key => $field) {
    //
    //            if (isset($value[$index][$key])) {
    //                $values[$index][$key] = $field->postProcessor($value[$index][$key], $request);
    //            }
    //        }
    //    }
    //
    //    // Is taken care of in post processing
    //    // $values = json_encode($values);
    //    return $values;
    //}

    public function toArray()
    {
        return $this->rows->toArray();
    }

    // protected function getValueId()
    // {
    // 	return $this->name.'-value';
    // }

    // public function renderRowNameTemplate(){
    // 	$str = '
    // 	<script type="text/x-template" id="'.$this->name.'-rowname">';
    // 	$str .= $this->row_name_template;
    // 	$str .= '</script>
    // 	';
    // 	return $str;
    // }

    // public function jsonValues()
    // {

    // 	// dump($this->getValue());
    // 	$value = $this->getValue();

    // 	if ($value) {
    // 		return json_encode($value);
    // 	}

    // 	return json_encode([0 => []]);
    // 	// $value = $this->getValue();

    // 	// $values = [];

    // 	// foreach ((array) $this->getValue() as $key => $val) {

    // 	// }

    // 	// foreach ($this->getRows() as $i => $row) {

    // 	// 	// $vueJson[] = $i;
    // 	// 	foreach ($row as $element) {
    // 	// 		if ($element->isField()) {
    // 	// 			$values[$i][$element->key] = (isset($)) ? $element->value : null;
    // 	// 		} else {
    // 	// 			$values[$i][$element->key] = null;
    // 	// 		}
    // 	// 		// $values[$i][$element->key] = ($element->isField()) ? $element->value : null;
    // 	// 	}
    // 	// }

    // 	return json_encode($values);
    // }

    public function jsonSerialize()
    {
        return $this->rows->jsonSerialize();
    }

    public function toJson($options = null)
    {
        return $this->rows->toJson();
    }

    /**
     * Get an iterator for the items.
     *
     * @access    public
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->inputs->all());
    }

    /**
     * Determine if an element exists at an offset.
     *
     * @access    public
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->inputs->all());
    }

    // --------------------------------------------------------------------

    /**
     * Get an element at a given offset.
     *
     * @access    public
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->inputs[$key];
    }

    // --------------------------------------------------------------------

    /**
     * Set the element at a given offset.
     *
     * @access    public
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->inputs[$key] = $value;
    }

    // --------------------------------------------------------------------

    /**
     * Unset the element at a given offset.
     *
     * @access    public
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->inputs[$key]);
    }

    // --------------------------------------------------------------------

    /**
     * Count the number of elements in the collection.
     *
     * @access    public
     * @return int
     */
    public function count()
    {
        return $this->inputs->count();
    }

    // --------------------------------------------------------------------

    protected function createRow($rowIndex = 0, $values = [])
    {
        $group = [];
        $inputs = $this->getInputs();

        foreach ($inputs as $k => $i) {
            $input = clone $i;

            if (isset($values[$k])) {
                $input->setValue($values[$k]);

                // Needed for validation.
                $input->setScope($this->getName(true).'['.$rowIndex.'][]');
            }

            $group[$input->getKey()] = $input;
        }

        $this->rows[] = $group;

        return $group;
    }

    //public function getRules()
    //{
    //    foreach ($this->getInputs() as $input) {
    //        $rules = $input->getRules();
    //        if (!empty($rules)) {
    //
    //            foreach ($input->getRules() as $rule) {
    //                $this->addRule()
    //            }
    //            $this->setRule('meta.'.$input->key, $input->getRules(), $input->getValidationMessages(), $input->getValidationAttribute());
    //        }
    //    }
    //}

    // --------------------------------------------------------------------

    //protected function processFieldNames($fields)
    //{
    //    $f = [];
    //
    //    if ($fields) {
    //        foreach ($fields as $key => $val) {
    //            if (! $val instanceof FieldInterface) {
    //                $val['name'] = $key;
    //            }
    //            // $val['v-bind:id'] = "'".$this->name."-' + index + '-".$key."'";
    //            // $val['v-bind:name'] = "'".$this->getName()."[' + index + '][".$key."]'";
    //            // $val['attrs'][':data-index'] = 'index';
    //            // $val['attrs']['data-id'] = $key;
    //            // $val['attrs']['data-name'] = $this->name;
    //
    //            // $name = $this->getName($append);
    //
    //            // if ($name && ! empty($this->scope))
    //            // {
    //            // 	if ($this->isArrayScope())
    //            // 	{
    //            // 		$name = preg_replace('#(.+)(\[\])$#U', '$1['.$name.']', $this->getScope());
    //            // 	}
    //            // 	else
    //            // 	{
    //            // 		$name = $this->getScope().$name;
    //            // 	}
    //            // }
    //            // $val['attrs']['data-scope'] = $this->getScope() . '.' . $this->name;
    //            $f[$key] = $val;
    //        }
    //    }
    //
    //    return $f;
    //}
}