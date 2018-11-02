<?php
/**
 * User: qbhy
 * Date: 2018/11/1
 * Time: 下午5:02
 */

namespace Qbhy\MicroServicesCommonSdk\Notify;

use Closure;
use Illuminate\Http\Response;
use Qbhy\SimpleJwt\Exceptions\JWTException;
use Qbhy\SimpleJwt\JWTManager;

class NotifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('services-authorized-token');

        if (!empty($token)) {
            try {
                /** @var JWTManager $jwtManager */
                $jwtManager = app(JWTManager::class);
                $jwt        = $jwtManager->fromToken($token);

                if ($jwt->getPayload()['aid'] === config('micro-services.app.id') || $jwt->getHeaders()['t'] === 'notify') {
                    return $next($request);
                }
            } catch (JWTException $exception) {
            }
        }


        return new Response('Forbidden!', 403);
    }
}
