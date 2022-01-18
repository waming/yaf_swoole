#!/usr/bin/env php
<?php
/**
 * @author honghm
 * @link https://www.github.com/waming
 *
 *  入口文件
 */

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');

error_reporting(E_ALL);

! defined('APP_PATH') && define('APP_PATH', dirname(__DIR__, 1));
! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);

// 判断是否存在autoload
if (file_exists(APP_PATH.'/vendor/autoload.php')) {
    // 增加自动加载
    include APP_PATH.'/vendor/autoload.php';
}

(function(){
    /** @var array $config */
    $config = require APP_PATH . '/config/ServerConfig.php';
    require APP_PATH . '/server/HttpServer.php';
    require APP_PATH . '/server/Request.php';
    /** start http server */
    (new HttpServer($config))->start();
})();


