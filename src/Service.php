<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午4:49
 */

namespace Qbhy\MicroServicesCommonSdk;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;

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
     * @param array $params
     * @param null $paramsType
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array
     */
    public function request(string $method, string $uri, array $params = [], $paramsType = null)
    {
        return $this->client->request($method, $this->prefix() . '/' . $uri, $params, $paramsType);
    }

    /**
     * 服务前缀
     *
     * @return string
     */
    abstract public function prefix(): string;

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param $method
     * @param $url
     * @param $params
     * @param null $paramsType
     * @return array|string
     * @throws Exceptions\UndefinedAppException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function frontendRequest($method, $url, $params, $paramsType = null)
    {
        $host   = str_contains($url, '://') ? '' : env('MICRO_SERVICE_BASE_URI');
        $client = $this->getClient();
        try {
            $response = $client->getHttp()->request($method, $host . $url, [
                Client::paramsType($method, $paramsType) => $params,
                RequestOptions::HEADERS                  => [
                    'aid' => $client->getConfig()->getAppConfig()['id'],
                ]
            ]);
        } catch (BadResponseException $exception) {
            throw $exception;
//            $response = $exception->getResponse();
        }

        return $client->formatResponse($response);
    }

}