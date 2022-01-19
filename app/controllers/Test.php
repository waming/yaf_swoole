<?php

use Server\Utils\Context;

class TestController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        //get request
        /** @var Yaf_Response_Abstract $request */
        $request = Context::get(Yaf_Request_Abstract::class);

        //从上下文中获取response对象
        /** @var Yaf_Response_Abstract $response */
        $response = Context::get(Yaf_Response_Abstract::class);

        //设置返回

        $response->setHeader("Content-Type", 'application/json; charset=utf-8;');

        $data = json_encode(['id' => 1]);
        $response->setBody(
            $data
        );
    }
}