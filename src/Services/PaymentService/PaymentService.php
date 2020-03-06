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
     * @param PayableOrder $order
     * @param UserServiceSubject $user
     * @param array $optional
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay(UserServiceSubject $user, PayableOrder $order, array $optional = [])
    {
        $paymentInfo = $this->request('post', '', array_merge([
            'client_ip' => $order->getClientIp(),
            'payable_amount' => $order->getPayableAmount(),
            'subject' => $order->getSubject(),
            'body' => $order->getBody(),
            'app_trade_id' => $order->getAppTradeId(),
            'oid' => $user->getOid(),
        ], $optional));

        $order->savePaymentInfo($paymentInfo);

        return $paymentInfo['pay_config'];
    }

    /**
     * @param UserServiceSubject $user
     * @param TransferableOrder $transfer
     * @param array $optional
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transferToUser(UserServiceSubject $user, TransferableOrder $transfer, array $optional = [])
    {
        return $this->request('post', 'transfer', array_merge([
            'client_ip' => $transfer->getClientIp(),
            'app_transfer_id' => $transfer->getAppTransferId(),
            'amount' => $transfer->getAmount(),
            'check_name' => $transfer->getCheckName(),
            'real_name' => $transfer->getRealName(),
            'transfer_reason' => $transfer->getTransferReason(),
            'payee_account' => $transfer->getPayeeAccount(),
            'oid' => $user->getOid(),
        ], $optional));
    }

    /**
     * @param RefundableOrder $order
     * @param array $optional
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refund(RefundableOrder $order, array $optional = [])
    {
        return $this->request('post', 'refund', array_merge([
            'app_refund_id' => $order->getAppRefundId(),
            'refund_amount' => $order->getRefundAmount(),
            'refund_reason' => $order->getRefundReason(),
            'payment_id' => $order->getPaymentId(),
        ], $optional));
    }
}