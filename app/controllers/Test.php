<?php

use Server\Utils\Context;

class TestController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        //get request
        $request = Context::get(Yaf_Request_Abstract::class);

        //从上下文中获取response对象
        $response = Context::get(Yaf_Response_Abstract::class);

        //设置返回
        $response->setBody(
            "hello,world"
        );
    }
}