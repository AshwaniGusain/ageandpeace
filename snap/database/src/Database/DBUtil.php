<?php namespace Snap\Database;

use DB;
use Schema;
use Config;
use PDO;

class DBUtil {

    protected static $columnListCache = [];

    /**
     * Escapes strings for database querying.
     *
     * @param  string  $str
     * @return string
     */
    public static function escape($str)
    {
        return DB::connection()->getPdo()->quote($str);
    }
    
    /**
     * Outputs the last query run.
     * \DB::enableQueryLog(); must be run first though since it is no longer enabled by default.
     *
     * @param  boolean  $all
     * @param  string  $format screen|comment|console
     * @param  bool  $return
     * @return string
     */
    public static function debug($all = false, $format = 'screen', $return = false)
    {
        $queries = DB::getQueryLog();

        $output = [];
        foreach($queries as $key => $query) {
            if( ! empty($query['bindings'])) {

                foreach($query['bindings'] as $binding) {
                    $query['query'] = preg_replace('/\?/', self::escape($binding), $query['query'], 1);
                }
            }
            $output[] = $query['query'];
        }

        if (!$all) {
            $output = end($output);
        }

        $str = '';
        $output = print_r($output, true);
        switch($format) {
            case 'screen':
                $str .= '<pre>';
                $str .= $output;
                $str .= '</pre>';
                break;
            case 'comment':
                $str .= '<!--';
                $str .= $output;
                $str .= '-->';
                break;
            case 'console':
                $str .= '<script>';
                $str .= 'console.log("'.query_escape($output).'")';
                $str .= '</script>';
                break;
        }

        if ($return) {
            return $str;
        }
        
        echo $str;
    }
    
    /**
     * Returns the names of the table's fields in an array.
     *
     * @return array
     */
    public static function columnList($table, $fresh = false)
    {
        if (empty(static::$columnListCache[$table]) || $fresh) {
            static::$columnListCache[$table] = collect(Schema::getColumnListing($table));
        }
        
        return static::$columnListCache[$table];
    }

    /**
     * Returns the column information from a table.
     *
     * @param  string  $table
     * @param  string  $column
     * @return array
     */
    public static function columnInfo($table, $column)
    {
        $tableInfo = self::tableInfo($table);

        // If a column is specified, return just that columns info.
        if (isset($tableInfo[$column])) {
            return collect($tableInfo[$column]);
        }
    }

    /**
     * Returns the table's column schema information.
     * First tried Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails($table);
     * however, there was trouble with support of enums and was a bit over complicated.
     *
     * @return array
     */
    public static function tableInfo($table)
    {
        $query = DB::select(DB::raw("SHOW FULL COLUMNS FROM ".$table));

        $fields = [];
        foreach($query as $field) {
            $type = $field->Type;
            $length = null;
            $options = null;

            preg_match('#([^(]+)(\((.+)\))?#', $type, $matches);
            $type = sizeof($matches) > 1 ? $matches[1] : $field->Type;
            if ( ! empty($matches[3]) AND strpos($matches[3], ',') > 0) {
                // convert enum dividers to single quotes in case they are part of the enum options
                $search = ["','", "''", "'", "__SQUOTE__"]; 
                $replace = ['__SEP__', '__SQUOTE__', '', "'"];
                $enum_vals = str_replace($search, $replace, $matches[3]);
                $options = explode("__SEP__", $enum_vals);
            } else {
                $length = sizeof($matches) > 3 ? $matches[3] : null;
            }

            $f = [];
            $f['name']          = $field->Field;
            $f['type']          = $type;
            $f['length']        = isset($length) ? $length : null;
            $f['options']       = isset($options) ? $options: null;
            $f['default']       = isset($field->Default) ? $field->Default: null;
            $f['primary_key']   = ($field->Key == "PRI") ? true : false;
            $f['comment']       = (isset($field->Comment) && $field->Comment !== '') ? $field->Comment : null;
            $f['null']          = ($field->Null == "NO") ? true : false;
            $f['auto_increment'] = (bool) (strpos($field->Extra, 'auto_increment') !== false);
            $f['unsigned']      = (bool) (strpos($f['type'], 'unsigned') !== false);
            $fields[$f['name']] = $f;
        }
        
        return collect($fields);
    }

    /**
     *  Returns a more generic column type of string, number, date, datetime, time, blob, enum. 
     *  If the column is not a recognized generic column type, it will simply return it's column type.
     *
     * @access  public
     * @param   string  $type
     * @return  string
     */ 
    public static function genericColumnType($type)
    {
        switch($type) {
            case 'var' : case 'varchar': case 'string': case 'tinytext': case 'text':  case 'longtext':
                return 'string';
            case 'int': case 'tinyint': case 'smallint': case 'mediumint': case 'float':  case 'double':  case 'decimal':
                return 'number';
            case 'datetime': case 'timestamp': case 'date':case 'year':
                return 'date';
            case 'time':
                return 'time';
            case 'blob': case 'mediumblob': case 'longblob':  case 'binary':
                return 'blob';
            case 'geometry': case 'point': case 'linestring':  case 'polygon': case 'multipoint': case 'multilinestring': case 'geometrycollection':
                return 'geometry';
            case 'enum':
                return 'enum';
            default:
                return $type;
        }
    }

    /**
     *  Returns the column name(s) for specific index types 
     *
     * @access  public
     * @param   string  $table
     * @return  array
     */ 
    public static function tableIndexes($table)
    {
        $query = DB::select(DB::raw("SHOW INDEX FROM ".$table));
        
        $indexes = [];

        if ( ! empty($query)) {
            foreach($query as $column) {

                $col = $column->Column_name;
                if (strpos($column->Key_name, 'PRI') !== false && $column->Non_unique == 0) {
                    $indexes['primary'] = $col;
                } elseif ($column->Non_unique == 0) {
                    $indexes['unique'][] = $col;
                } else {
                    $indexes['index'][] = $col;
                }
            }
        }

        return $indexes;
    }

    /**
     * Returns a collection of joins in a query.
     *
     * @param $query
     * @return \Illuminate\Support\Collection
     */
    public static function joins($query)
    {
        return collect($query->getQuery()->joins);
    }

    /**
     * Determine if a join exists already on a query.
     *
     * @param $query
     * @param $table
     * @return bool
     */
    public static function joinExists($query, $table)
    {
        return static::joins($query)->pluck('table')->contains($table);
    }

}
