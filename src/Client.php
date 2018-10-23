<?php

/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午5:00
 */

namespace Qbhy\MicroServicesCommonSdk;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

class Client
{
    protected $config;

    /** @var HttpClient */
    protected $http;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return HttpClient
     */
    public function getHttp()
    {
        if (is_null($this->http)) {
            $this->http = new HttpClient([
                'base_uri' => $this->config->get('base_uri'),
            ]);
        }

        return $this->http;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $params
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, string $uri, array $params = [], $paramsType = null)
    {
        try {
            $response = $this->getHttp()->request($method, $uri, [
                Client::paramsType($method, $paramsType) => $params,
            ]);
        } catch (BadResponseException $exception) {
            throw $exception;
//            $response = $exception->getResponse();
        }

        return $this->formatResponse($response);
    }

    public static function paramsType(string $method, $paramsType)
    {
        if ($paramsType === null) {
            $method = strtolower($method);
            return $method === 'get' ? RequestOptions::QUERY : RequestOptions::JSON;
        }

        return $paramsType;
    }

    /**
     * @param Response $response
     *
     * @return mixed
     */
    public function formatResponse(Response $response)
    {
        return @\GuzzleHttp\json_decode($response->getBody()->__toString(), true);
    }
}