<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午6:17
 */

namespace Qbhy\MicroServicesCommonSdk\Services\Auth;

class ExampleService extends \Qbhy\MicroServicesCommonSdk\Service
{
    public function prefix(): string
    {
        return 'test';
    }

    /**
     * @param array $data
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array
     */
    public function test(array $data)
    {
        return $this->request('post', 'test', $data);
    }

}