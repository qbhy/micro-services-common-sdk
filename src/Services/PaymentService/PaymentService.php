<?php
/**
 * User: qbhy
 * Date: 2018/10/30
 * Time: 下午5:13
 */

namespace Qbhy\MicroServicesCommonSdk\Services\PaymentService;

use Qbhy\MicroServicesCommonSdk\Service;
use Qbhy\MicroServicesCommonSdk\Services\UserService\Auth\UserServiceSubject;

class PaymentService extends Service
{
    public function prefix(): string
    {
        return 'payment';
    }

    /**
     * @param PayableOrder       $order
     * @param UserServiceSubject $user
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay(UserServiceSubject $user, PayableOrder $order)
    {
        return $this->request('post', '', [
            'client_ip'      => $order->getClientIp(),
            'payable_amount' => $order->getPayableAmount(),
            'subject'        => $order->getSubject(),
            'body'           => $order->getBody(),
            'app_trade_id'   => $order->getAppTradeId(),
            'oid'            => $user->getOid(),
        ]);
    }

}