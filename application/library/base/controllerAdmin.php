<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2017/11/22
 * Time: 15:29
 */
class base_controllerAdmin extends base_controller
{

    public function init()
    {
        parent::init();
        $this->_view->assign('menu', menuModel::admin());
    }

    /**
     * 包含头尾的完整网页显示
     * @param $name
     * @param bool $header
     * @param bool $footer
     */
    public function show($name, $header = true, $footer = true)
    {
        if ($header) {
            parent::display('../header');
        }
        $this->display($name);
        if ($footer) {
            parent::display('../footer');
        }
    }


}