<?php
/**
 * User: qbhy
 * Date: 2018/10/30
 * Time: 下午5:20
 */

namespace Qbhy\MicroServicesCommonSdk\Services\PaymentService;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait PayableOrderTrait
 *
 * @property string $client_ip
 * @property string $subject
 * @property string $body
 * @property string $trade_id
 * @property int    $amount_payable
 * @package Qbhy\MicroServicesCommonSdk\Services\PaymentService
 * @mixin Model
 * @mixin PayableOrder
 */
trait PayableOrderTrait
{
    public function getClientIp()
    {
        return $this->client_ip;
    }

    public function getPayableAmount()
    {
        return $this->amount_payable;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getAppTradeId()
    {
        return $this->trade_id;
    }

    public function savePaymentInfo(array $paymentInfo)
    {
        $this->setPaymentId($paymentInfo['service_payment_id']);
        $this->setServiceTradeId($paymentInfo['service_trade_id']);
        $this->save();
    }

}