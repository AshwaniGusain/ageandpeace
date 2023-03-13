<?php

namespace Snap\DataTable;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Snap\Support\Helpers\TextHelper;

class DataTableFormatters
{
    /**
     * Uses the trans() function against a specified key.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @param $trans
     * @return string
     */
    public static function transFormatter($columnData, $rowData, $key, $trans)
    {
        return trans($trans);
    }

    /**
     * Currency formatter.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function currencyFormatter($columnData, $rowData, $key)
    {
        return trans('datatable::formatters.currency_formatter').$columnData;
    }

    /**
     * Boolean formatter.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function booleanFormatter($columnData, $rowData, $key)
    {
        return ($columnData == 1) ? trans('datatable::formatters.boolean_formatter_yes') : trans('datatable::formatters.boolean_formatter_no');
    }

    /**
     * JSON data formatter.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function jsonFormatter($columnData, $rowData, $key)
    {
        if (is_json($columnData)) {
            $columnData = array_format($columnData, '<br>');
        }

        return $columnData;
    }

    /**
     * Implodes an array with a delimiter (default is ', '.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @param $delimiter
     * @return string
     */
    public static function implodeFormatter($columnData, $rowData, $key, $delimiter = ', ')
    {
        if (is_array($columnData)) {
            $columnData = implode($columnData, $delimiter);
        } elseif ($columnData instanceof Collection) {
            $columnData = $columnData->implode($delimiter);
        }

        return $columnData;
    }

    /**
     * A formatter to contain the size of the displayed data.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @param $maxHeight
     * @param $width
     * @return string
     */
    public static function containerFormatter($columnData, $rowData, $key, $maxHeight = '50', $width = 300)
    {
        return '<div style="width: '.$width.'px; max-height: '.$maxHeight.'px; overflow: auto;">'.$columnData.'</div>';
    }

    /**
     * A formatter to convert dates.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function dateFormatter($columnData, $rowData, $key)
    {
        if ($columnData instanceof \Carbon\Carbon) {
            return (new Carbon($columnData))->format(config('snap.admin.date_format'));
        }

        return $columnData;
    }

    /**
     * A formatter to convert empty values into an N/A.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function naFormatter($columnData, $rowData, $key)
    {
        return empty($columnData) ? trans('datatable::formatters.na_formatter') : $columnData;
    }

    /**
     * A formatter to convert urls into links.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function urlFormatter($columnData, $rowData, $key, $removeHttp = false)
    {
        $label = $columnData;

        if ($columnData == 'http://' || $columnData == '') {
            return '';
        }

        $url = parse_url($columnData);

        if (! $url || ! isset($url['scheme'])) {
            $columnData = 'http://'.$columnData;
        }

        if ($removeHttp) {
            $label = preg_replace("(^https?://)", "", $columnData);
        }

        return '<a href="'.$columnData.'" target="_blank">'.e($label).'</a>';
    }

    /**
     * A formatter to convert emails into mailto links.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function emailFormatter($columnData, $rowData, $key, $removeHttp = false)
    {
        return '<a href="mailto:'.$columnData.'">'.$columnData.'</a>';
    }

    /**
     * Wraps the data in a nowrap span to prevent breaking.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @return string
     */
    public static function nowrapFormatter($columnData, $rowData, $key)
    {
        return '<span style="white-space: nowrap;">'.$columnData.'</span>';
    }

    /**
     * Wraps the data in a div with a min width.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @param $minWidth
     * @param $maxWidth
     * @return string
     */
    public static function widthFormatter($columnData, $rowData, $key, $minWidth = 200, $maxWidth = 300)
    {
        if (is_numeric($minWidth)) {
            $minWidth = $minWidth.'px';
        }

        if (is_numeric($maxWidth)) {
            $maxWidth = $maxWidth.'px';
        }

        return '<div style="min-width: '.$minWidth.'; max-width: '.$maxWidth.';">'.$columnData.'</div>';
    }

    /**
     * Truncates the text to a certain character limit.
     *
     * @param $columnData
     * @param $rowData
     * @param $key
     * @param $charLimit
     * @return string
     */
    public static function truncateFormatter($columnData, $rowData, $key, $charLimit = 50)
    {
        return TextHelper::characterLimiter($columnData, $charLimit);
    }

}
