<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qbhy\MicroServicesCommonSdk\JwtParser;

use Illuminate\Http\Request;

class QueryString implements \Qbhy\MicroServicesCommonSdk\Auth\Parser
{
    use KeyTrait;

    /**
     * Try to parse the token from the request query string.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return null|string
     */
    public function parse(Request $request)
    {
        return $request->query($this->key);
    }
}
