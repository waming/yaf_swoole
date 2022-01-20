<?php

namespace Server\Http;


use Yaf_Response_Http;

class Response extends Yaf_Response_Http
{
    private static array $phrases
        = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-status',
            208 => 'Already Reported',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Switch Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested range not satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            511 => 'Network Authentication Required',
        ];

    private \Swoole\Http\Response $swooleResponse;

    public function __construct (\Swoole\Http\Response $swooleResponse)
    {
        parent::__construct();
        $this->swooleResponse = $swooleResponse;
    }

    /**
     * 设置返回code
     * @param int $code
     */
    public function setStatusCode(int $code)
    {
        $this->_response_code = $code;
    }

    public function getStatusCode() : int
    {
        return $this->_response_code ?? 200;
    }

    /**
     * @override
     */
    public function setRedirect($url) : bool
    {
        $this->setHeader("Location", $url);
        $this->setStatusCode(302);
        return true;
    }

    /**
     * 返回响应体
     */
    public function emit()
    {
        $this->buildSwooleResponse();
        $this->swooleResponse->end($this->getBody());
    }

    private function buildSwooleResponse()
    {
        /*
         * Headers
         */
        foreach ($this->getHeader() as $key => $value) {
            $this->swooleResponse->header($key, $value);
        }

        /**
         * cookies
         */

        /*
         * Status code
         */
        $this->swooleResponse->status($this->getStatusCode(), self::$phrases[$this->getStatusCode()] ?? '');
    }
}