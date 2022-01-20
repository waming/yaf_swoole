<?php

use Server\Http\Request;
use Server\Http\Response;
use Server\Utils\Context;

class TestController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        //get request
        /** @var Request $request */
        $request = Context::get(Yaf_Request_Abstract::class);

        //get response
        /** @var Response $response */
        $response = Context::get(Yaf_Response_Abstract::class);

        //get post data
        $data = $request->getPosts();
        $str = '';
        foreach($data as $k => $v) {
            $str .= $k .'='. $v. "\r\n";
        }

        //bug can not use var_dump function

        //direct
        //$response->setRedirect("https://www.baidu.com/");

        //response code
        //$response->setStatusCode(404);

        //response header
        //$response->setHeader("Content-Type", 'application/json; charset=utf-8;');

        //return data
        //$data = json_encode(['id' => 1]);
        $response->setBody(
            $str
        );
    }
}