<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2017/11/22
 * Time: 15:31
 */
class IndexController extends base_controllerAdmin
{

    public function indexAction()
    {
        echo 'Jaackie Admin!';
    }

    public function helloAction()
    {
        $this->_view->assign('name', $this->get('name', 'Jaackie'));
        $this->display('hello');
    }

}