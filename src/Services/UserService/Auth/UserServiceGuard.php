<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Qbhy\MicroServicesCommonSdk\Services\UserService\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Qbhy\MicroServicesCommonSdk\JwtParser\Parser;
use Qbhy\SimpleJwt\Exceptions\JWTException;
use Qbhy\SimpleJwt\JWTManager;

class UserServiceGuard implements Guard
{
    use GuardHelpers;

    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

    /**
     * The JWT manager instance.
     *
     * @var JWTManager
     */
    protected $jwt;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Instantiate the class.
     *
     * @param JWTManager               $jwt
     * @param UserServiceUserProvider  $provider
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function __construct(JWTManager $jwt, UserServiceUserProvider $provider, Request $request)
    {
        $this->jwt      = $jwt;
        $this->provider = $provider;
        $this->request  = $request;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \Qbhy\SimpleJwt\Exceptions\InvalidTokenException
     * @throws \Qbhy\SimpleJwt\Exceptions\SignatureException
     * @throws \Qbhy\SimpleJwt\Exceptions\TokenExpiredException
     */
    public function user($token = null)
    {
        if ($token === null && $this->user !== null) {
            return $this->user;
        }
        try {
            $token = $token ?? app(Parser::class)->setRequest($this->request)->parseToken();

            if ($token && ($payload = $this->jwt->fromToken($token)->getPayload())) {
                return $this->user = $this->provider->retrieveById($payload);
            }
        } catch (JWTException $exception) {
            return $this->user = null;
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return (bool)$this->attempt($credentials, false);
    }

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param array $credentials
     * @param bool  $login
     *
     * @return bool|string
     */
    public function attempt(array $credentials = [], $login = true)
    {
        /** @var UserServiceSubject $user */
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * Create a token for a user.
     *
     * @param UserServiceSubject $user
     *
     * @return string
     */
    public function login(UserServiceSubject $user)
    {
        $this->user = $user;
        return true;
    }

    /**
     * Logout the user, thus invalidating the token.
     *
     * @param bool $forceForever
     *
     * @return void
     */
    public function logout($forceForever = false)
    {
        $this->user = null;
    }

    /**
     * Refresh the token.
     *
     * @param bool $forceForever
     * @param bool $resetClaims
     *
     * @return string
     */
    public function refresh($forceForever = false, $resetClaims = false)
    {

    }

    /**
     * Get the user provider used by the guard.
     *
     * @return UserServiceUserProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set the user provider used by the guard.
     *
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     *
     * @return $this
     */
    public function setProvider(UserProvider $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Return the currently cached user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the current request instance.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request ?: Request::createFromGlobals();
    }

    /**
     * Set the current request instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the last user we attempted to authenticate.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function getLastAttempted()
    {
        return $this->lastAttempted;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

}
