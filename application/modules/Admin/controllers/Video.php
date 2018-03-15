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
        var_dump($res);
    }

    private static function _createDir($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

}