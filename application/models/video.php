<?php
/**
 * User: Jaackie
 * Date: 2018/3/9
 */

class videoModel extends base_model
{
    /*  `video_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(128)  NOT NULL,
  `intro` varchar(256) NOT NULL DEFAULT ' ',
  `time_length` varchar(10) NOT NULL DEFAULT ' ',
  `cover` varchar(128)  NOT NULL DEFAULT ' ',
  `view_num` int unsigned NOT NULL DEFAULT 0,
  `like_num` int unsigned NOT NULL default 0,
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL,*/

    public $video;
    public $url;
    public $into = '';
    public $time_length = '';
    public $cover = '';
    public $view_num = 0;
    public $like_num = 0;
    public $create_time = 0;
    public $update_time = 0;

    protected $__table_name = 'video';


}