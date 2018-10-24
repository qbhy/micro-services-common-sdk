<?php
/**
 * User: qbhy
 * Date: 2018/7/25
 * Time: 下午3:51
 */

namespace Qbhy\MicroServicesCommonSdk\Services\UserService\Auth;


interface UserServiceSubject
{
    public static function fromUserService(array $userinfo);

    public function getOid();

    public function getUid();

}