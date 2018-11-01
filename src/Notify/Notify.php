<?php
/**
 * User: qbhy
 * Date: 2018/11/1
 * Time: 下午5:46
 */

namespace Qbhy\MicroServicesCommonSdk\Notify;

use Illuminate\Http\Request;
use Qbhy\MicroServicesCommonSdk\Notify\Handlers\Handler;
use Qbhy\MicroServicesCommonSdk\Notify\Handlers\PaymentNotify;

class Notify
{
    const NOTIFY_HANDLERS = [
        'payment' => PaymentNotify::class,
    ];

    public static function handle($result)
    {
        $handlerClass = Notify::NOTIFY_HANDLERS[$result['type']];

        /** @var Handler $handler */
        $handler = new $handlerClass($result['status'], $result['data']);

        return $handler->handle();
    }
}