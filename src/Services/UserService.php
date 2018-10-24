<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午6:17
 */

namespace Qbhy\MicroServicesCommonSdk\Services;

class UserService extends \Qbhy\MicroServicesCommonSdk\Service
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
    public function userinfo(int $oid, int $uid = 0)
    {
        return $this->request('get', $oid, compact('uid'));
    }

}