<?php

namespace Snap\Docs;

use Snap\Support\Helpers\UtilHelper;

class FuncReplacer
{
    /**
     * @var string
     */
    protected $leftDelimiter = '{{';

    /**
     * @var string
     */
    protected $rightDelimiter = '}}';

    /**
     * @var array
     */
    protected $funcs = [];

    /**
     * @var string
     */
    protected $html = '';

    /**
     * Replacer constructor.
     *
     * @param $html
     * @param $funcs
     */
    public function __construct($funcs = [], $html = '')
    {
        $this->setFuncs($funcs);
        $this->setHtml($html);
    }

    /**
     *
     */
    public function run()
    {
        $html = $this->html;
        foreach ($this->funcs as $func) {
            $pattern = preg_quote($this->leftDelimiter).'\s*('.$func.'\(.*\))*\s*'.preg_quote($this->rightDelimiter);
            $html = preg_replace_callback('#'.$pattern.'#U', function($matches){

                if (!empty($matches[1])) {
                    return UtilHelper::evalStr('<?='.$matches[1].'?>');
                }

                return $matches[0];
            }, $html);

        }

        return $html;
    }

    /**
     * @return array
     */
    public function getFuncs(): array
    {
        return $this->funcs;
    }

    /**
     * @param array $funcs
     * @return $this
     */
    public function setFuncs(array $funcs)
    {
        $this->funcs = $funcs;

        return $this;
    }

    /**
     * @param $func
     * @return $this
     */
    public function add($func)
    {
        if (is_array($func)) {
            foreach ($func as $f) {
                $this->add($f);
            }
        } else {

            if (config('snap.docs.allowed_funcs') !== false) {
                $this->funcs[] = $func;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @param string $html
     * @return $this
     */
    public function setHtml(string $html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return string
     */
    public function getLeftDelimiter(): string
    {
        return $this->leftDelimiter;
    }

    /**
     * @param string $leftDelimiter
     * @return $this
     */
    public function setLeftDelimiter(string $leftDelimiter)
    {
        $this->leftDelimiter = $leftDelimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getRightDelimiter(): string
    {
        return $this->rightDelimiter;
    }

    /**
     * @param string $rightDelimiter
     * @return $this
     */
    public function setRightDelimiter(string $rightDelimiter)
    {
        $this->rightDelimiter = $rightDelimiter;

        return $this;
    }

}