<?php

namespace Snap\DataTable;

interface DataTableJsSorterInterface
{
    public function setSortedColumn($column);

    public function getSortedColumn();

    public function getJsFunc();

    public function render();
}
