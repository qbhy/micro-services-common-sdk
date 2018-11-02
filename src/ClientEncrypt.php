<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午5:08
 */

namespace Qbhy\MicroServicesCommonSdk;


use Qbhy\SimpleJwt\EncryptAdapters\PasswordHashEncrypter;

class ClientEncrypt extends PasswordHashEncrypter
{
    protected $config;

    public function __construct(Config $config)
    {
        parent::__construct('');

        $this->config = $config;
    }

    /**
     * @return string
     * @throws Exceptions\UndefinedAppException
     */
    public function getSecret(): string
    {
        return $this->config->getAppConfig()['secret'];
    }
}