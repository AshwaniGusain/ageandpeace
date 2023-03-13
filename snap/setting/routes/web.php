<?php
Route::any('/{uri?}', [
    'uses' => '\Snap\Page\Http\Controllers\PageController@page',
])
     ->where(['uri' => '.*'])
     ->name('page_content');