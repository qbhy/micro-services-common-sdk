<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午4:49
 */

namespace Qbhy\MicroServicesCommonSdk;

abstract class Service
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $params
     * @param null   $paramsType
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array
     */
    public function request(string $method, string $uri, array $params = [], $paramsType = null)
    {
        return $this->client->request($method, $this->prefix().'/' . $uri, $params, $paramsType);
    }

    /**
     * 服务前缀
     *
     * @return string
     */
    abstract public function prefix(): string;

}