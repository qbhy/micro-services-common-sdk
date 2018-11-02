<?php
/**
 * User: qbhy
 * Date: 2018/10/30
 * Time: 下午5:20
 */

namespace Qbhy\MicroServicesCommonSdk\Services\PaymentService;


interface PayableOrder
{
    public function getClientIp();

    public function getPayableAmount();

    public function getSubject();

    public function getBody();

    public function getAppTradeId();

    public function savePaymentInfo(array $paymentInfo);

    public function setPaymentId(int $paymentId);

    public function setServiceTradeId(string $serviceTradeId);

}