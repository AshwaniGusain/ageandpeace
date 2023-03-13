<?php

namespace Snap\Admin\Ui\Formatters;

class EditInline
{
    /**
     * Makes a static piece of data editable.
     *
     * @param $columnData
     * @param $rowData
     * @return string
     */
    public static function format($columnData, $rowData, $key, $module = null)
    {
        js('scripts', 'assets/snap/js/components/form/EditInlinePopover.js');

        if (empty($module)) {
            $module = \Admin::modules()->current();
        }

        if (is_string($module)) {
            $module = \Admin::modules()->get($module);
        }

        if (strpos($key, '.') !== false) {
            $keyParts = explode('.', $key);
            array_pop($keyParts);
            $key = implode('.', $keyParts).'_id';
        }

        $idField = $module->getModel()->getKeyName();
        $editUrl = $module->url('input/'.$key.'/'.$rowData[$idField]);
        $updateUrl = $module->url('update', [$rowData[$idField]]);

        $module->loadFormJs();

        return '<snap-edit-inline-popover edit-url="'.$editUrl.'" update-url="'.$updateUrl.'" input="'.$key.'">' . $columnData . '</snap-edit-inline-popover>';
    }


}
