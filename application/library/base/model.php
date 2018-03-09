<?php

/**
 * Created by PhpStorm.
 * User: jaackie
 * Date: 2017/3/11
 * Time: 下午4:20
 */
class base_model
{
    /**
     * 表名
     * @var string
     */
    protected $__table_name = '';


    /**
     * @param string $id
     * @return $this
     */
    public static function instance($id = '')
    {
        static $instances = [];
        $class = get_called_class();
        $key = $id !== '' ? $class . $id : $class;
        if (!isset($instances[$key])) {
            $instances[$key] = new $class($id);
        }

        return $instances[$key];
    }

    /**
     * @param null $tableName
     * @return Table
     */
    public function table($tableName = null)
    {
        return Table::instance($tableName === null ? $this->__table_name : $tableName);
    }

}