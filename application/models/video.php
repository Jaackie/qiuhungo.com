<?php
/**
 * User: Jaackie
 * Date: 2018/3/9
 */

class videoModel extends base_model
{
    public $video_id;
    public $url;
    public $intro = '';
    public $time_length = '';
    public $cover = '';
    public $view_num = 0;
    public $like_num = 0;
    public $create_time = 0;
    public $update_time = 0;

    protected $__table_name = 'video';
    protected $__primary_key = 'video_id';

    public function __construct($videoId = 0)
    {
        $this->setVideoId($videoId);
    }

    /**
     * @param int $video_id
     * @return $this
     */
    public function setVideoId($video_id)
    {
        $this->video_id = (int)$video_id;
        return $this;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
     * @param string $time_length
     * @return $this
     */
    public function setTimeLength($time_length)
    {
        $this->time_length = $time_length;
        return $this;
    }

    /**
     * @param string $cover
     * @return $this
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * @param int $view_num
     * @param bool $isIncrease
     * @return $this
     */
    public function setViewNum($view_num = 1, $isIncrease = true)
    {
        if ($isIncrease) {
            $this->view_num += $view_num;
        } else {
            $this->view_num = $view_num;
        }

        return $this;
    }

    /**
     * @param int $like_num
     * @param bool $isIncrease
     * @return $this
     */
    public function setLikeNum($like_num = 1, $isIncrease = true)
    {
        if ($isIncrease) {
            $this->like_num += $like_num;
        } else {
            $this->like_num = $like_num;
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function setCreateTime()
    {
        $this->create_time = time();
        return $this;
    }

    /**
     * @return $this
     */
    private function setUpdateTime()
    {
        $this->update_time = time();
        return $this;
    }

    public function add()
    {
        if (!$this->url) return false;

        $this->setCreateTime()->setUpdateTime();
        return $this->table()->insert([
            'url' => $this->url,
            'intro' => $this->intro,
            'time_length' => $this->time_length,
            'cover' => $this->cover,
            'view_num' => $this->view_num,
            'like_num' => $this->like_num,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time
        ]);
    }


}