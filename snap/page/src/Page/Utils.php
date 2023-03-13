<?php

namespace Snap\Page;

class Utils
{
    public static function pageTitle($title = '', $sep = null, $order = 'right')
    {
        $defaultPageTitle = config('snap.pages.page_title_default');
        $titleArr = [];
        if (! isset($sep)) {
            $sep = config('snap.pages.page_title_separator');
        }
        if ($order == 'left' && !empty($defaultPageTitle)) {
            $titleArr[] = $defaultPageTitle;
        }

        if (is_array($title)) {
            $titleArr += $title;
        } else {
            if (! empty($title)) {
                array_push($titleArr, $title);
            }
        }
        if ($order == 'right' && !empty($defaultPageTitle)) {
            $titleArr[] = $defaultPageTitle;
        }

        return implode($sep, $titleArr);
    }
}