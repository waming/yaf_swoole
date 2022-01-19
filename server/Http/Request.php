<?php
/**
 * 自定义request类 需继承 Yaf_Request_Abstract
 */
namespace Server\Http;

use JetBrains\PhpStorm\Pure;
use Swoole\Http\Request as SwooleRequest;
use Yaf_Request_Abstract;

class Request extends Yaf_Request_Abstract
{
    private SwooleRequest $swooleRequest;

    private array $server;

    protected string $myuri;

    public function __construct(SwooleRequest $request)
    {
        $this->swooleRequest = $request;
        $this->server = $request->server;
        $this->myuri = $this->server['request_uri'];
        $this->setRequestUri($this->myuri);
    }

    public function clearParams(): object
    {
        return $this;
    }

    public function getActionName() : string
    {
        return $this->uri;
    }

    public function getBaseUri(): string
    {
        return $this->uri;
    }

    public function getControllerName(): string
    {
        return $this->uri;
    }

    public function getEnv(string $name = null, $default = null): mixed
    {
        return $this->server[$name] ?? $default;
    }

    #[Pure] public function getException() : \Exception
    {
        return new \Exception();
    }

    public function getLanguage(): string
    {
        return 'zh-ch';
    }

    public function getMethod() : string
    {
        return $this->server['request_method'];
    }

    public function getModuleName() : string
    {
        return $this->uri;
    }

    public function getParam($name = "", $default = "") : mixed
    {
        return $this->swooleRequest->get($name) ?? $default;
    }

    public function getParams(): array
    {
        return $this->swooleRequest->get() ?? [];
    }

    public function setRequestUri($uri) : object
    {
        parent::setRequestUri($uri);
        return $this;
    }

    public function getRequestUri() : string
    {
        return $this->uri;
    }

    public function getServer($name = null,  $default = null) :mixed
    {
        return $this->server[$name] ?? $default;
    }

    public function isCli(): bool
    {
        return false;
    }

    public function isGet(): bool
    {
        return ($this->getMethod()) == 'GET';
    }

    public function isHead(): bool {
        return count($this->swooleRequest->header) > 0;
    }

    public function isOptions() : bool {
        return true;
    }

    public function isPost(): bool {
        return ($this->getMethod()) == 'POST';
    }

    public function isPut(): bool
    {
        return ($this->getMethod()) == 'PUT';
    }

    public function header($name) : array
    {
        return $this->swooleRequest->header[$name] ?? "";
    }

    public function isXmlHttpRequest(): bool
    {
        return $this->header('X-Requested-With') == 'XMLHttpRequest';
    }
}