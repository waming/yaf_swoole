#!/usr/bin/env php
<?php
/**
 * @author honghm
 * @link https://www.github.com/waming
 *
 *  入口文件
 */

use Server\Http\HttpServer;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');

error_reporting(E_ALL);

! defined('APP_PATH') && define('APP_PATH', dirname(__DIR__, 1));
! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);

include APP_PATH.'/vendor/autoload.php';

(function(){
    /** @var array $config */
    $config = require APP_PATH . '/config/ServerConfig.php';
    /** start Http server */
    (new HttpServer($config))->start();
})();


