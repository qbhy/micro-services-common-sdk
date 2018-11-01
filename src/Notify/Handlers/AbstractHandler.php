<?php
/**
 * User: qbhy
 * Date: 2018/11/1
 * Time: 下午5:55
 */

namespace Qbhy\MicroServicesCommonSdk\Notify\Handlers;


abstract class AbstractHandler implements Handler
{
    /** @var int */
    protected $status;

    /** @var array */
    protected $data;

    public function __construct($status, $data)
    {
        $this->data   = $data;
        $this->status = $status;
    }
}