<?php
/**
 * http启动类
 * Class HttpServer
 */

namespace Server\Http;

use RuntimeException;
use Server\Utils\Context;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server as SwooleServer;;
use Swoole\Http\Server as SwooleHttpServer;
use Swoole\Coroutine;
use Yaf_Application;
use Yaf_Exception;
use Yaf_Exception_DispatchFailed;
use Yaf_Exception_LoadFailed;
use Yaf_Exception_LoadFailed_Action;
use Yaf_Exception_LoadFailed_Controller;
use Yaf_Exception_RouterFailed;
use Yaf_Exception_StartupError;
use Yaf_Exception_TypeError;
use Yaf_Request_Abstract;
use Yaf_Response_Abstract;
use Server\Http\Request as CoRequest;
use Server\Http\Response as CoResponse;

class HttpServer
{
    private ?SwooleServer $server = null;

    private array $config;

    private ?Yaf_Application $yafApplication = null;

    /**
     * HttpServer constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->initServer();
    }

    /**
     * init server
     */
    public function initServer()
    {
        if (! $this->server instanceof SwooleServer) {
            $this->server = new SwooleHttpServer($this->config['host'], $this->config['port'], $this->config['mode'], $this->config['sock_type']);
            $this->server->set($this->config['settings']);
            $this->registerEvents();
        }
    }

    public function start()
    {
        Coroutine::set(['hook_flags' => SWOOLE_HOOK_FLAGS]);
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

    public function registerWorkerStart(SwooleServer $server, int $workerId)
    {
        if ($server->taskworker) {
            echo "TaskWorker{$workerId} started. \r\n";
        } else {
            echo "Worker{$workerId} started. \r\n";
        }

        if (! $this->yafApplication instanceof Yaf_Application) {
            //Yaf object
            try {
                $this->yafApplication = new Yaf_Application(APP_PATH . '/config/app.ini', 'product');
                $this->yafApplication->bootstrap();
                $this->yafApplication->getDispatcher()
                                    ->autoRender(false)  //close render
                                    ->setErrorHandler([$this, "errorHander"], E_ALL); //set error handler
            } catch (Yaf_Exception_StartupError | Yaf_Exception_TypeError $e) {
                throw new RuntimeException($e->getMessage(), $e->getCode());
            }
        }
    }

    /**
     * @throws \Yaf_Exception_TypeError
     * @throws \Yaf_Exception_DispatchFailed
     * @throws \Yaf_Exception_LoadFailed_Controller
     * @throws \Yaf_Exception_RouterFailed
     * @throws \Yaf_Exception_LoadFailed
     * @throws \Yaf_Exception_LoadFailed_Action
     */
    public function registerRequest(Request $request, Response $response)
    {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
            $response->end();
            return;
        }

        $myrequest = new CoRequest($request);
        $coResponse = new CoResponse($response);

        //save request to Coroutine
        Context::set(Yaf_Request_Abstract::class, $myrequest);
        Context::set(Yaf_Response_Abstract::class, $coResponse);

        /**
         * set Yaf request
         */
        $this->yafApplication->getDispatcher()->dispatch($myrequest);
        $coResponse->emit();
    }

    /**
     * @doc https://www.laruence.com/manual/yaf.class.dispatcher.setErrorHandler.html
     * Yaf error hander
     * You can use LogHandler. return use JSON or XML
     */
    public function errorHander($errno, $errstr, $errfile, $errline)
    {
        /** @var \Server\Http\Response $coResponse */
        $coResponse = Context::get(Yaf_Response_Abstract::class);
        $coResponse->setStatusCode($errno);
        $coResponse->setBody($errstr);
    }
}