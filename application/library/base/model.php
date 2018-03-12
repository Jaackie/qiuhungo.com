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
     * @var Table 表对象
     */
    protected $__table;

    /**
     * @var string 主键
     */
    protected $__primary_key = 'id';

    /**
     * @var array 查询单条记录信息
     */
    protected $__info = [];

    /**
     * @var bool 是否初始化
     */
    protected $__is_init = false;


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
            $this->__table = Table::instance();
        }
        return $this->__table->setTable($tableName === null ? $this->__table_name : $tableName);
    }

    /**
     * 查询一条记录，支持字段联合查询，以','号分隔，默认主键。（对象模型中的字段和值确保存在）
     * @param string $key
     * @return array
     */
    public function find($key = null)
    {
        $key = $key ?: $this->__primary_key;
        $keyArr = explode(',', $key);

        foreach ($keyArr as $field) {
            if (!property_exists($this, $field)) return [];
            $this->table()->whereField($field, $this->$field);
        }

        return $this->table()->getOne();
    }

    /**
     * 初始化对象模型，支持直接用数组初始化，或者根据needle中的字段查询
     * @param string|array $initNeedle
     * @return $this
     */
    public function init($initNeedle = null)
    {
        $find = is_array($initNeedle) ? $initNeedle : $this->find($initNeedle);
        if ($find) {
            $is_init = false;
            foreach ($find as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                    $is_init = true;
                }
            }
            $this->__is_init = $is_init;
        }
        return $this;
    }

    /**
     * 查询的单条数据（初始化后生成）
     * @return array
     */
    public function info()
    {
        return $this->__info;
    }

    /**
     * 是否初始化
     * @return bool
     */
    public function isInit()
    {
        return $this->__is_init;
    }

    /**
     * 更新
     * @param $field
     * @param bool $emptyCheck
     * @return bool|int
     * @throws
     */
    public function save($field, $emptyCheck = false)
    {
        $id = $this->{$this->__primary_key};
        if (!$id) return false;

        $field_arr = is_array($field) ? $field : explode(',', $field);
        $is_set = false;
        foreach ($field_arr as $field) {
            if (property_exists($this, $field) && !($emptyCheck && empty($this->$field))) {
                $this->table()->set($field, $this->$field);
                $is_set = true;
            }
        }
        if (!$is_set) return false;

        return $this->table()->whereField($this->__primary_key, $id)->update();
    }

    /**
     * 删除
     * @return bool|int
     * @throws
     */
    public function delete()
    {
        $id = $this->{$this->__primary_key};
        if (!$id) return false;

        return $this->table()->whereField($this->__primary_key, $id)->delete();
    }

}