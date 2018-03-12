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

    protected $__table;


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
        if (!$this->__table) {
            $this->__table = Table::instance($this->__table_name);
        }
        return $this->__table->setTable($tableName === null ? $this->__table_name : $tableName);
    }

    /**
     * 查询一条记录，支持字段联合查询，以','号分隔，默认主键。（对象模型中的字段和值确保存在）
     * @param string $key
     * @return array
     */
    protected function find($key = null)
    {
       /* $key = $key ?: $this->primaryKey;
        $keyArr = explode(',', $key);

        foreach ($keyArr as $field) {
            $transField = self::_transField($field);
            if (!property_exists($this, $transField)) return [];
            $this->db()->where($field, $this->$transField);
        }

        return $this->db()->select()->from($this->table)->fetch();*/
    }

}