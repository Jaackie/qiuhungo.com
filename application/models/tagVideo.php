<?php
/**
 * User: Jaackie
 * Date: 2018/3/13
 */

class tagVideoModel extends base_model
{
    public $id;
    public $tag_id;
    public $video_id;
    public $create_time;

    protected $__table_name = 'tag_video';
    protected $__primary_key = 'id';

    public function __construct($id = 0)
    {

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
     * @param mixed $tag_id
     * @return $this
     */
    public function setTagId($tag_id)
    {
        $this->tag_id = (int)$tag_id;
        return $this;
    }

    /**
     * @param mixed $video_id
     * @return $this
     */
    public function setVideoId($video_id)
    {
        $this->video_id = (int)$video_id;
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
     * @return array
     */
    public function findByTagIdVideoId()
    {
        return $this->find('tag_id,video_id');
    }


    /**
     * @return bool|int
     */
    private function _insert()
    {
        if (!$this->tag_id || !$this->video_id) return false;

        $this->_setCreateTime();
        return $this->table()->insert([
            'tag_id' => $this->tag_id,
            'video_id' => $this->video_id,
            'create_time' => $this->create_time
        ]);
    }

    /**
     * @param $tagIdArr
     * @return bool|int
     */
    private function _insertMulti($tagIdArr)
    {
        if (!$this->video_id || !$tagIdArr) return false;

        $insert = [];
        $this->_setCreateTime();
        foreach ($tagIdArr as $tag_id) {
            if ($tag_id) {
                $insert[] = [
                    'tag_id' => $tag_id,
                    'video_id' => $this->tag_id,
                    'create_time' => $this->create_time,
                ];
            }
        }
        if (!$insert) return false;

        return $this->table()->insertMulti($insert);
    }

    /**
     * @return bool|int
     */
    public function add()
    {
        if (!$this->tag_id || !$this->video_id) return false;

        if ($find = $this->findByTagIdVideoId()) {
            return $find['id'];
        }

        $this->_setCreateTime();
        return $this->_insert();
    }


    /**
     * 通过tag字符串添加
     * @param $str
     * @return bool|int
     */
    public function addByTagStr($str)
    {
        if (!$str || !$this->video_id) return 0;

        $tag_name_arr = explode(',', str_replace('，', ',', $str));
        $tag = new tagModel();
        $tag_id_arr = [];
        foreach ($tag_name_arr as $tag_name) {
            $tag_id_arr[] = $tag->setTagName($tag_name)->add();
        }

        return $this->_insertMulti($tag_id_arr);
    }


}