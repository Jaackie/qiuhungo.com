<?php
/**
 * User: Jaackie
 * Date: 2018/3/13
 */

class tagModel extends base_model
{
    public $tag_id;
    public $tag_name = '';
    public $intro = '';
    public $create_time = 0;
    public $update_time = 0;

    protected $__table_name = 'tag';
    protected $__primary_key = 'tag_id';

    public function __construct($tagId = 0)
    {
        $this->setTagId($tagId);
    }

    /**
     * @param mixed $tag_id
     * @return $this
     */
    public function setTagId($tag_id)
    {
        $this->tag_id = (int)$tag_id;
        return $this;
    }

    /**
     * @param string $tag_name
     * @return $this
     */
    public function setTagName($tag_name)
    {
        $this->tag_name = trim($tag_name);
        return $this;
    }

    /**
     * @param string $intro
     * @return $this
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
        return $this;
    }

    /**
     * @return $this
     */
    private function _setCreateTime()
    {
        $this->create_time = time();
        return $this;
    }

    /**
     * @return $this
     */
    private function _setUpdateTime()
    {
        $this->update_time = time();
        return $this;
    }

    /**
     * @return array
     */
    public function findByName()
    {
        return $this->find('tag_name');
    }

    /**
     * @return bool|int
     */
    private function _insert()
    {
        if (!$this->tag_name) return false;

        $this->_setCreateTime()->_setUpdateTime();
        return $this->table()->insert([
            'tag_name' => $this->tag_name,
            'intro' => $this->intro,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time
        ]);
    }

    /**
     * @return bool|int
     */
    public function add()
    {
        if (!$this->tag_name) return false;

        if ($find = $this->findByName()) {
            return $find['tag_id'];
        }

        return $this->_insert();
    }


}