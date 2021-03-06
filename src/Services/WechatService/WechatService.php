<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午6:17
 */

namespace Qbhy\MicroServicesCommonSdk\Services\WechatService;

use Qbhy\MicroServicesCommonSdk\Service;
use Qbhy\MicroServicesCommonSdk\Services\UserService\Auth\UserServiceSubject;

class WechatService extends Service
{
    public function prefix(): string
    {
        return 'wechat';
    }

    /**
     * @param  array  $data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendTemplateMsg(UserServiceSubject $user, $templateId, $formId, $page, array $data)
    {
        return $this->request('post', 'mini-app-template-msg', [
            'oid' => $user->getOid(),
            'template_id' => $templateId,
            'form_id' => $formId,
            'page' => $page,
            'data' => $data,
        ]);
    }

    /**
     * @param  array  $data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSubscribeMsg(UserServiceSubject $user, $templateId, $page, array $data)
    {
        return $this->request('post', 'mini-app-subscribe-msg', [
            'oid' => $user->getOid(),
            'template_id' => $templateId,
            'page' => $page,
            'data' => $data,
        ]);
    }

    /**
     * @param $content
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function contentTextVerify($content)
    {
        return $this->request('post', 'mini-app-check-text', [
            'content' => $content,
        ]);
    }

    /**
     * @param $page
     *
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException
     */
    public function smallProgramCode($page)
    {
        return $this->frontendRequest('get', 'wx-app/code', compact('page'));
    }

    /**
     * @param $code
     * @param $encryptedData
     * @param $iv
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function decryptData($code, $encryptedData, $iv)
    {
        return $this->request('POST', 'mini-app-decrypt-data', compact('code', 'encryptedData', 'iv'));
    }

    public function officialUserFromCode($code, $cname = 'official')
    {
        return $this->request('GET', 'official-account/userinfo-code', compact('code', 'cname'));
    }
}