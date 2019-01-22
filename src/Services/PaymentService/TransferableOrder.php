<?php
/**
 * User: qbhy
 * Date: 2018/10/30
 * Time: 下午5:20
 */

namespace Qbhy\MicroServicesCommonSdk\Services\PaymentService;


interface TransferableOrder
{
    public function getClientIp();

    public function getTransferReason();

    public function getAmount();

    public function getCheckName();

    public function getRealName();

    public function getAppTransferId();

    public function getPayeeAccount();

}