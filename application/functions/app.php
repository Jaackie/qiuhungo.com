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

function is_ajax()
{
    if (isset($_REQUEST['is_ajax'])) {
        return $_REQUEST['is_ajax'] != 0;
    }
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

/**
 * 打印数据
 */
function __d()
{
    $args = func_get_args();
    echo "<pre>";
    foreach ($args as $arg) {
        var_export($arg);
    }
    echo "</pre>";
}

/**
 * 打印数据并退出
 */
function __dd()
{
    $args = func_get_args();
    echo "<pre>";
    foreach ($args as $arg) {
        var_export($arg);
    }
    echo "</pre>";
    exit;
}

