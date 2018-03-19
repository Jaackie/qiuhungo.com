<?php
/**
 * User: Jaackie
 * Date: 2018/3/19
 */

class configModel extends base_model
{
    public $id;
    public $key;
    public $value = '';

    protected $__table_name = 'config';
    protected $__primary_key = 'id';

    public function __construct($id = 0)
    {
        $this->setId($id);
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    /**
     * @param mixed $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
        return $this;
    }

    /**
     * @return $this
     */
    public function initByKey()
    {
        return $this->init('key');
    }

    /**
     * @return bool|int
     */
    public function add()
    {
        if (!$this->key) return false;

        return $this->table()->insert([
            'key' => $this->key,
            'value' => $this->value,
        ]);
    }

    /**
     * @return bool|int
     */
    public function update()
    {
        if (!$this->key) return false;

        return $this->save('value');
    }

    /**
     * @param bool $isArr
     * @return mixed|string
     */
    public function getValue($isArr = false)
    {
        if ($isArr) {
            return json_decode($this->value, true);
        }
        return $this->value;
    }


}