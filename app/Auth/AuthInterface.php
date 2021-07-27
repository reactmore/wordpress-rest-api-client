<?php

namespace Reactmore\WordpressClient\Auth;

use Psr\Http\Message\RequestInterface;

/**
 * Interface AuthInterface
 * @package Reactmore\WordpressClient\Auth
 */
interface AuthInterface
{
    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function addCredentials(RequestInterface $request);
}
