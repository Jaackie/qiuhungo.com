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
        $res = $this->table()->insert([
            'tag_id' => $this->tag_id,
            'video_id' => $this->video_id,
            'create_time' => $this->create_time
        ]);
        if ($res) {
            tagModel::instance()->setTagId($this->tag_id)->updateVideoNum(1, '+');
        }
        return $res;
    }

    /**
     * @return bool|int
     */
    private function _del()
    {
        $res = $this->delete();
        if ($res) {
            tagModel::instance()->setTagId($this->tag_id)->updateVideoNum(1, '+');
        }
        return $res;
    }


    /**
     * 通过tag字符串保存的
     * @param $str
     * @return bool|int
     */
    public function saveByTagStr($str)
    {
        if (!$this->video_id) return 0;

        $res = 0;

        $tag_video_list = $this->getListByVideoId(true);    //原有的关联


        $tag_id_arr = [];   //新的关联
        if ($str) {
            $tag_name_arr = explode(',', str_replace('，', ',', $str));
            $tag = tagModel::instance();
            foreach ($tag_name_arr as $tag_name) {
                $tag_id_arr[] = $tag->setTagName($tag_name)->add();
            }
        }

        if ($tag_id_arr) {
            foreach ($tag_id_arr as $tag_id) {
                if (!isset($tag_video_list[$tag_id])) {
                    $res_add = $this->setTagId($tag_id)->_insert(); //增加的
                    !$res_add ?: $res++;
                }
            }
        }

        if ($tag_video_list) {
            foreach ($tag_video_list as $tag_id => $tag_video) {
                if (!in_array($tag_id, $tag_id_arr)) {
                    $res_del = $this->setId($tag_video['id'])->setTagId($tag_id)->_del();   //删除的
                    !$res_del ?: $res++;
                }
            }
        }

        return $res;
    }

    /**
     * @param bool $assoc
     * @return array
     */
    public function getListByVideoId($assoc = false)
    {
        if (!$this->video_id) return [];

        $list = $this->table()->whereField('video_id', $this->video_id)->get();
        if ($assoc) {
            tool_arr::assocByKey($list, 'tag_id');
        }
        return $list;
    }

    /**
     * 给视频列表添加上tag信息
     * @param $videoList
     * @param bool $withTagInfo
     */
    public function videoListWithTagList(&$videoList, $withTagInfo = false)
    {
        if (!$videoList) return;

        $video_id_arr = tool_arr::getKeyArrFromArr($videoList, 'video_id');
        $list = $this->table()->whereIn('video_id', $video_id_arr)->get();
        if ($withTagInfo) {
            tagModel::instance()->withTagInfo($list);
        }
        tool_arr::mergeArrMulti($videoList, $list, 'video_id', 'tags');
    }

    /**
     * 给单个视频添加上tag信息
     * @param $videoInfo
     * @param bool $withTagInfo
     */
    public function videoWithTagList(&$videoInfo, $withTagInfo = false)
    {
        if (!$videoInfo) return;

        $videoList = [$videoInfo];
        $this->videoListWithTagList($videoList, $withTagInfo);
        $videoInfo = $videoList[0];
    }


}