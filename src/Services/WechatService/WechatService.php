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
     * @param array $data
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array
     */
    public function sendTemplateMsg(UserServiceSubject $user, $templateId, $formId, $page, array $data)
    {
        return $this->request('post', 'mini-app-template-msg', [
            'oid'         => $user->getOid(),
            'template_id' => $templateId,
            'form_id'     => $formId,
            'page'        => $page,
            'data'        => $data,
        ]);
    }

    /**
     * @param $content
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
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException
     */
    public function smallProgramCode($page)
    {
        return $this->frontendRequest('get', 'wx-app/code', compact('page'));
    }

}