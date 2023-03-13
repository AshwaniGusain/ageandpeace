<?php

namespace Snap\Database\Model\Traits;

use Illuminate\Support\Arr;
use Snap\Database\DBUtil;

trait HasTableSchema {

    /**
     * Returns the names of the table's fields in an array.
     *
     * @return array
     */
    public function columnList()
    {
        if (is_null($this->_cache['columnlist'])) {
            $columnList = DBUtil::columnList($this->getTable());
            $this->_cache['columnlist'] = $columnList;
        }

        return $this->_cache['columnlist'];
    }

    /**
     * Returns the column information from a table.
     *
     * @param  string  $column
     * @return array
     */
    public function columnInfo($column)
    {
        // We only need to do this once.
        if (!isset($this->_cache['columninfo'][$column])) {
            $columnInfo = DBUtil::columnInfo($this->getTable(), $column);
            if (in_array($column, $this->booleanColumns())) {
                $columnInfo['type'] = 'boolean';
            }
            $this->_cache['columninfo'][$column] = $columnInfo;
        }

        return $this->_cache['columninfo'][$column];
    }

    /**
     * Returns the table's information.
     * First tried Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails($this->table);
     * however, there was trouble with support of enums and was a bit over complicated.
     *
     * @return array
     */
    public function tableInfo()
    {
        // We only need to do this once.
        if (is_null($this->_cache['tableinfo'])) {
            $fields = DBUtil::tableInfo($this->getTable());
            $this->_cache['tableinfo'] = $fields;
        }

        return $this->_cache['tableinfo'];
    }

    /**
     *  Returns a more generic column type of string, number, date, datetime, time, blob, enum.
     *  If the column is not a recognized generic column type, it will simply return it's column type.
     *
     * @access  public
     * @param   string  $column
     * @return  string
     */
    public function genericColumnType($column)
    {
        $columnInfo = $this->columnInfo($column);
        $type = $columnInfo['type'];

        return DBUtil::genericColumnType($type);
    }

    /**
     * Returns the table's index information.
     *
     * @return array
     */
    public function tableIndexes()
    {
        // We only need to do this once.
        if (is_null($this->_cache['indexes'])) {
            $indexes = DBUtil::tableIndexes($this->getTable());
            $this->_cache['indexes'] = $indexes;
        }
        return $this->_cache['indexes'];
    }

    /**
     * Returns any column names that are registered to the booleans property.
     *
     * @return array
     */
    public function booleanColumns()
    {
        if (property_exists($this, 'booleans')) {
            return $this->booleans;
        }

        $booleans = [];
        foreach ($this->casts as $field => $cast) {
            if ($cast == 'boolean') {
                $booleans[] = $field;
            }
        }

        return $booleans;
    }

    /**
     * Returns column(s) that are specified to have unique indexes.
     *
     * @return array
     */
    public function uniqueColumns()
    {
        if (property_exists($this, 'unique')) {
            return Arr::flatten($this->unique);
        }

        return null;
    }


}
