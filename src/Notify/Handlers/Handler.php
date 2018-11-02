<?php
/**
 * User: qbhy
 * Date: 2018/11/1
 * Time: 下午5:51
 */

namespace Qbhy\MicroServicesCommonSdk\Notify\Handlers;

use Symfony\Component\HttpFoundation\Response;

interface Handler
{
    /**
     * @return Response
     */
    public function handle();
}