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
use Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException;
use Qbhy\SimpleJwt\Interfaces\Encoder;
use Qbhy\SimpleJwt\JWTManager;

class Client
{
    const AUTH_HEADER = 'app-authorized-token';

    /** @var Config */
    protected $config;

    /** @var ClientEncrypt */
    protected $encrypt;

    /** @var JWTManager */
    protected $jwtManager;

    /** @var HttpClient */
    protected $http;

    public function __construct(Config $config, Encoder $encoder)
    {
        $this->config     = $config;
        $this->encrypt    = new ClientEncrypt($this->config);
        $this->jwtManager = new JWTManager($this->encrypt, $encoder);
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
     * @throws UndefinedAppException
     */
    public function request(string $method, string $uri, array $params = [], $paramsType = null)
    {
        try {
            $response = $this->getHttp()->request($method, $uri, [
                Client::paramsType($method, $paramsType) => $params,
                RequestOptions::HEADERS                  => [
                    Client::AUTH_HEADER => $this->token(),
                ]
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
     * @return array|string
     */
    public function formatResponse(Response $response)
    {
        if ($result = @json_decode($raw = $response->getBody()->__toString(), true)) {
            return $result;
        }

        return $raw;
    }

    /**
     * @return string
     * @throws Exceptions\UndefinedAppException
     */
    public function token()
    {
        return $this->jwtManager->make(['aid' => $this->config->getAppConfig()['id']])->token();
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
}