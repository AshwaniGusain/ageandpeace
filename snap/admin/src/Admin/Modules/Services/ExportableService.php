<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;

/*
$exportable = ExportableService::make();
$exportable
    ->fileName('download.csv')
    ->columns('name', 'active')
    ->format(function($val, $data){
        return ($val) == 1 ? 'Yes' : 'No';
    }, ['active'])
;
*/
class ExportableService
{
    public $fileName = 'download.csv';
    public $formatters = [];
    public $columns = [];

    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;

        $this->fileName = $this->module->pluralName().'-'.date('Y-m-d').'.csv';
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function fileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function columns($columns)
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->columns = $columns;

        return $this;
    }

    public function format($formatter, $columns = null)
    {
        if (is_array($formatter)) {
            foreach ($formatter as $key => $val) {
                $this->format($key, $val);
            }
        } else {
            $this->formatters[] = [$formatter, $columns];
        }

        return $this;
    }

    public function getData()
    {
        $data = $this->module->getQuery()->get()->toArray();

        $defaultColumns = $this->determineHeaders($data);

        $newData = [];

        foreach ($data as $index => $row) {

            $newData[$index] = [];

            foreach ($defaultColumns as $c) {
                $value = $row[$c];

                foreach ($this->formatters as $f) {
                    $formatter = $f[0];
                    $formatColumns = $f[1];

                    if (is_int($formatter)) {
                        $formatter = $formatColumns;
                        $formatColumns = $defaultColumns;

                    } elseif (empty($formatColumns) || $formatColumns == '*') {
                        $formatColumns = $defaultColumns;
                    }

                    if (in_array($c, $formatColumns)) {
                        $value = call_user_func($formatter, $row[$c], $row);
                    }
                }

                $newData[$index][$c] = (string) $value;
            }
        }

        return $newData;
    }

    protected function determineHeaders($data)
    {
        if (!empty($this->columns)) {
            return $this->columns;
        }

        return array_keys(current($data));
    }

    public function download()
    {
        $data = $this->getData();

        $output = [];
        if (!empty($data)) {
            $output[] = implode(',', $this->determineHeaders($data));
            foreach ($data as $i => $row) {
                $output[] = implode(',', $row);

            }
        }

        $output = implode("\n", $output);

        // headers used to make the file "downloadable", we set them manually
        // since we can't use Laravel's Response::download() function
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$this->fileName.'"',
        );

        // our response, this will be equivalent to your download() but
        // without using a local file
        return \Response::make(rtrim($output, "\n"), 200, $headers);
    }
}