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

    public function signature(string $signatureString): string
    {
        return password_hash($signatureString . $this->config->get('app.secret'), PASSWORD_BCRYPT);
    }

    public function check(string $signatureString, string $signature): bool
    {
        return password_verify($signatureString . $this->config->get('app.secret'), $signature);
    }
}