<?php
/**
 * Created by PhpStorm.
 * User: marico
 * Date: 2017/3/17
 * Time: 下午4:51
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
    /**
     * 初始化session
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initSession(Yaf_Dispatcher $dispatcher)
    {
        //$handler = new RedisSession(config::get('cache'));
        //session_set_save_handler($handler, true);
        //Yaf_Session::getInstance()->start();
    }

    /**
     * 初始化路由一个路由
     * @param Yaf_Dispatcher $dispatcher
     * @throws Yaf_Exception_TypeError
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        $router = $dispatcher->getRouter();

        $centerPid = new Yaf_Route_Regex(
            '#center/([0-9]+)#', [
            'module' => 'System',
            'controller' => 'Index',
            'action' => 'index',
        ],[1=>'pid']
        );
        $admin_route =  new Yaf_Route_Rewrite(
            '/center', [
                'module' => 'System',
                'controller' => 'Index',
                'action' => 'index',
            ]
        );

        $route = new Yaf_Route_Rewrite('u/:id', ['controller' => 'uri', 'action' => 'index']);
        $orderRoute = new Yaf_Route_Rewrite('/order/:id', ['controller' => 'order', 'action' => 'index']);
        // 使用路由器装载路由协议
        $router->addRoute('codeIndex', $route);
        $router->addRoute('orderIndex', $orderRoute);
        $router->addRoute('center', $admin_route);
        $router->addRoute('centerPid', $centerPid);
    }
}