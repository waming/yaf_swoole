<?php
use Swoole\Coroutine;

class TestController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        //从上下文中获取response对象
        $response = Coroutine::getContext()[Yaf_Response_Http::class];

        //设置返回
        $response->setBody(
            "hello,world"
        );
    }
}