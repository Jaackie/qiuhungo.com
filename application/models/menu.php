<?php
/**
 * User: Jaackie
 * Date: 2018/3/15
 */

class menuModel
{
    public static function admin()
    {
        return [
            /*'index' => [
                'name' => '首页',
                'icon' => 'icon-home',
                'action' => 'hello',
            ],*/
            'video' => [
                'name' => '视频管理',
                'icon' => 'icon-file-movie',
                'action' => [
                    'index' => '列表',
                    'upload' => '上传',
                ],
            ],
            'config' => [
                'name' => '配置管理',
                'icon' => 'icon-sliders',
                'action' => [
                    'index' => '首页',
                ],
            ],
        ];
    }

}