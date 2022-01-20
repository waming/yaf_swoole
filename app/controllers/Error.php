<?php
/**
 * 当有未捕获的异常, 则控制流会流到这里
 */
class ErrorController extends Yaf_Controller_Abstract
{
    /**
     * 初始化控制器
     */
    public function init()
    {
        // 关闭视图渲染
        Yaf_Dispatcher::getInstance()->disableView();
    }

    /**
     * 异常处理控制器
     * @param Exception $exception
     */
    public function errorAction()
    {
        /** @var \Server\Http\Request $request */
        $request = \Server\Utils\Context::get(Yaf_Request_Abstract::class);

        $exception = $request->getException();
        $message = $request->getRequestUri();

        $this->ajaxReturn(false, $message);
    }

    /**
     * ajaxReturn,返回ajax请求
     * @param bool $status
     * @param string|array $info
     * @param array $param
     * @param string $url
     * @param string $encoding
     */
    protected function ajaxReturn(bool $status = false, $info = '', $param = [], string $url = '', string $encoding = 'utf-8')
    {
        // 判断是否为数组
        if (is_array($info)) {
            $data = $info;
            $data['status'] = $status;
        } else {
            $data = [
                'status' => $status,
                'info' => $info,
            ];
            empty($param) || $data['param'] = $param;
            empty($url) || $data['url'] = $url;
        }

        /** @var \Server\Http\Response $response */
        $response = \Server\Utils\Context::get(Yaf_Response_Abstract::class);
        $response->setHeader("Content-type", "application/json;charset=$encoding");
        $response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
