<?php

declare(strict_types=1);

use Swoole\Constant;

return [
    'mode' => SWOOLE_PROCESS,
    'name' => 'http',
    'host' => '0.0.0.0',
    'port' => 9501,
    'sock_type' => SWOOLE_SOCK_TCP,
    'settings' => [
        Constant::OPTION_ENABLE_COROUTINE => true,
        Constant::OPTION_WORKER_NUM => swoole_cpu_num(),
        Constant::OPTION_PID_FILE => APP_PATH . '/runtime/swoole.pid',
        Constant::OPTION_OPEN_TCP_NODELAY => true,
        Constant::OPTION_MAX_COROUTINE => 100000,
        Constant::OPTION_OPEN_HTTP2_PROTOCOL => true,
        Constant::OPTION_MAX_REQUEST => 100000,
        Constant::OPTION_SOCKET_BUFFER_SIZE => 2 * 1024 * 1024,
        Constant::OPTION_BUFFER_OUTPUT_SIZE => 2 * 1024 * 1024,
    ]
];