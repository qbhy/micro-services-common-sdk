<?php
/**
 * User: qbhy
 * Date: 2018/11/1
 * Time: 下午5:46
 */

namespace Qbhy\MicroServicesCommonSdk\Notify;

use Qbhy\MicroServicesCommonSdk\Config;
use Qbhy\MicroServicesCommonSdk\Notify\Handlers\Handler;

class Notify
{
    /**
     * @param $result
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException
     * @throws UndefinedHandlerException
     */
    public static function handle($result)
    {
        /** @var Config $config */
        $config = app(Config::class);

        $handlers = $config->getAppConfig()['handlers'];

        if (isset($handlers[$result['type']])) {
            $handlerClass = $handlers[$result['type']];

            /** @var Handler $handler */
            $handler = new $handlerClass($result['status'], $result['data']);

            return $handler->handle();
        }

        throw new UndefinedHandlerException('未定义 ' . $result['type'] . ' 处理程序!');
    }
}