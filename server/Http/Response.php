<?php

namespace Server\Http;


use Yaf_Response_Http;

class Response extends Yaf_Response_Http
{
    /**
     * 返回响应体
     */
    public function emit(\Swoole\Http\Response $swooleResponse)
    {
        $this->buildSwooleResponse($swooleResponse);
        $swooleResponse->end($this->getBody());
    }

    private function buildSwooleResponse(\Swoole\Http\Response $swooleResponse)
    {
        /*
         * Headers
         */
        foreach ($this->getHeader() as $key => $value) {
            $swooleResponse->header($key, $value);
        }

        /**
         * cookies
         */

        /*
         * Status code
         */
        $swooleResponse->status($this->_response_code);
    }
}