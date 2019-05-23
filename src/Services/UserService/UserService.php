<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午6:17
 */

namespace Qbhy\MicroServicesCommonSdk\Services\UserService;

use Qbhy\MicroServicesCommonSdk\Service;

class UserService extends Service
{
    public function prefix(): string
    {
        return 'user';
    }

    /**
     * @param array $data
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array
     */
    public function userinfo(int $oid, int $uid = null)
    {
        return $this->request('get', $oid, compact('uid'));
    }

}