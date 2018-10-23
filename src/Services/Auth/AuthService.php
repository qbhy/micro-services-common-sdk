<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午6:07
 */

namespace Qbhy\MicroServicesCommonSdk\Services\Auth;

use Qbhy\MicroServicesCommonSdk\Service;

class AuthService extends Service
{
    public function prefix(): string
    {
        return 'auth/';
    }

}