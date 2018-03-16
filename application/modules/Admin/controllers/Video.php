<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2017/11/22
 * Time: 15:31
 */
class VideoController extends base_controllerAdmin
{

    public function uploadAction()
    {
        $this->show('upload');
    }

    public function addAction()
    {
        list($dir, $path) = videoModel::createPathName();
        self::_createDir($dir);

        $res = move_uploaded_file($_FILES["file"]["tmp_name"], $path);
        if (!$res) $this->__errorAjax('视频上传失败');

        $video_id = videoModel::instance()->setUrl($path)->add();
        if (!$video_id) {
            $this->__errorAjax('视频生成失败');
        }
        $this->__successAjax(['video_id' => $video_id]);
    }

    public function saveAction()
    {
        $video_id = $this->requirePost('video_id');
        $intro = $this->post('intro', '');
        $time_length = $this->post('time_length', '');
        $tag_str = $this->post('tag_str');

        $video = videoModel::instance($video_id)->init();
        if (!$video->isInit()) {
            $this->__errorAjax('该视频不存在');
        }
        $res_info = $video->setIntro($intro)->setTimeLength($time_length)->saveInfo();
        if (!$res_info) {
            $this->__errorAjax('保存信息失败');
        }
        if ($tag_str) {
            $res_tags = tagVideoModel::instance()->setVideoId($video_id)->addByTagStr($tag_str);
            if (!$res_tags) {
                $this->__errorAjax('保存分类信息失败');
            }
        }
        $this->__successAjax();
    }

    private static function _createDir($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

}