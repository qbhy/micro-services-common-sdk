<?php
/**
 * User: qbhy
 * Date: 2018/10/30
 * Time: 下午5:20
 */

namespace Qbhy\MicroServicesCommonSdk\Services\PaymentService;


interface RefundableOrder
{
    public function getAppRefundId();

    public function getRefundAmount();

    public function getRefundReason();

    public function getPaymentId();

}