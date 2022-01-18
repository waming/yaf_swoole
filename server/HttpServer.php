<?php
/**
 * http启动类
 * Class HttpServer
 */

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server as SwooleServer;;
use Swoole\Http\Server as SwooleHttpServer;
use Swoole\Coroutine;

class HttpServer
{
    protected ?SwooleServer $server = null;

    private array $config;

    private ?Yaf_Application $yafApplication = null;

    protected static array $nonCoContext = [];

    /**
     * 初始化
     * HttpServer constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initServer();
    }

    /**
     * 初始化server方法
     */
    public function initServer()
    {
        $this->writePid();

        if (! $this->server instanceof SwooleServer) {
            $this->server = new SwooleHttpServer($this->config['host'], $this->config['port'], $this->config['mode'], $this->config['sock_type']);
            $this->server->set($this->config['settings']);
            $this->registerEvents();
        }
    }

    public function start()
    {
        Coroutine::set(['hook_flags' => SWOOLE_HOOK_FLAGS]); //一键协程化
        $this->server->start();
    }

    /**
     * register event
     * @see https://wiki.swoole.com/#/server/events
     */
    private function registerEvents()
    {
        $this->server->on('start', [$this, 'registerStart']);
        $this->server->on('workerStart', [$this, 'registerWorkerStart']);
        $this->server->on('request', [$this, 'registerRequest']);
    }

    public function registerStart(): void
    {
         echo "start event! \r\n";
    }

    public function registerWorkerStart()
    {
        echo "workerStart event! \r\n";

        if (! $this->yafApplication instanceof Yaf_Application) {
            //创建yaf对象
            try {
                $this->yafApplication = new Yaf_Application(APP_PATH . '/config/app.ini', 'develop');
                $this->yafApplication->bootstrap();
            } catch (Yaf_Exception_StartupError | Yaf_Exception_TypeError $e) {
                throw new RuntimeException($e->getMessage(), $e->getCode());
            }
        }
    }

    private function writePid()
    {
        $file = $this->config['settings']['pid_file'];
        file_put_contents($file, getmypid());
    }

    /**
     * @throws Yaf_Exception_TypeError
     * @throws Yaf_Exception_DispatchFailed
     * @throws Yaf_Exception_LoadFailed_Controller
     * @throws Yaf_Exception_RouterFailed
     * @throws Yaf_Exception_LoadFailed
     * @throws Yaf_Exception_LoadFailed_Action
     */
    public function registerRequest(Request $request, Response $response)
    {
        $myrequest = new \Server\Request($request);
        $yafresponse = new \Yaf_Response_Http();

        /** test */
        //是否在协程中
        if(Coroutine::getCid() > 0) {
            Coroutine::getContext()[Request::class] = $myrequest;
            Coroutine::getContext()[Yaf_Response_Http::class] = $yafresponse;
        } else {
            static::$nonCoContext[Request::class] = $myrequest;
            static::$nonCoContext[Yaf_Response_Http::class] = $yafresponse;
        }

        /**
         * 配置自定义request
         */
        $this->yafApplication->getDispatcher()->dispatch($myrequest);
        $response->end($yafresponse->getBody());
    }
}