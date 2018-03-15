<?php
/**
 * @author Jaackie <ljq4@meitu.com>
 * @Date 2018/3/14
 */

function r()
{
    $config = Yaf_Registry::get("config");
    return $config['application']['resource'];
}

function include_view($viewName, $module = 'Admin')
{
    include(APPLICATION_PATH . '/application/modules/' . $module . '/views/' . $viewName . '.phtml');
}
