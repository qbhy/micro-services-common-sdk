<?php
/**
 * User: qbhy
 * Date: 2018/11/2
 * Time: 下午6:23
 */

namespace Qbhy\MicroServicesCommonSdk\Notify;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class LumenNotifyController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws UndefinedHandlerException
     * @throws \Qbhy\MicroServicesCommonSdk\Exceptions\UndefinedAppException
     */
    public function notify(Request $request)
    {
        return Notify::handle($request->only(['type', 'status', 'data']));
    }
}