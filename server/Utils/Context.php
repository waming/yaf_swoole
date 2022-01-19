<?php

namespace Server\Utils;

use Swoole\Coroutine;

/**
 * Class Context
 * @package Server\Utils
 * 协程上下文类
 */
class Context
{
    protected static array $nonCoContext = [];

    /**
     * 是否处于协程中
     * @return bool
     */
    public static function isCo() : bool
    {
        return Coroutine::getCid() > 0;
    }

    private static function getContext() : mixed
    {
        return Coroutine::getContext();
    }

    /**
     * 保存数据
     */
    public static function set(string $id, $value)
    {
        if(self::isCo()) {
            self::getContext()[$id] = $value;
        } else {
            self::$nonCoContext[$id] = $value;
        }
        return $value;
    }

    /**
     * 获取数据
     */
    public static function get(string $id, $default = null)
    {
        if(self::isCo()) {
            return self::getContext()[$id] ?? $default;
        }
        return self::$nonCoContext[$id] ?? $default;
    }
}