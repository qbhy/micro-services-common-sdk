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
        $paymentInfo = $this->request('post', '', [
            'client_ip'      => $order->getClientIp(),
            'payable_amount' => $order->getPayableAmount(),
            'subject'        => $order->getSubject(),
            'body'           => $order->getBody(),
            'app_trade_id'   => $order->getAppTradeId(),
            'oid'            => $user->getOid(),
        ]);

        $order->savePaymentInfo($paymentInfo);

        return $paymentInfo['pay_config'];
    }

    /**
     * @param UserServiceSubject $user
     * @param TransferableOrder  $transfer
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transferToUser(UserServiceSubject $user, TransferableOrder $transfer)
    {
        return $this->request('post', 'transfer', [
            'client_ip'       => $transfer->getClientIp(),
            'app_transfer_id' => $transfer->getAppTransferId(),
            'amount'          => $transfer->getAmount(),
            'check_name'      => $transfer->getCheckName(),
            'real_name'       => $transfer->getRealName(),
            'transfer_reason' => $transfer->getTransferReason(),
            'payee_account'   => $transfer->getPayeeAccount(),
            'oid'             => $user->getOid(),
        ]);
    }

    /**
     * @param RefundableOrder $order
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refund(RefundableOrder $order)
    {
        return $this->request('post', 'refund', [
            'app_refund_id' => $order->getAppRefundId(),
            'refund_amount' => $order->getRefundAmount(),
            'refund_reason' => $order->getRefundReason(),
            'payment_id'    => $order->getPaymentId(),
        ]);
    }
}