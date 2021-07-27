<?php

namespace Reactmore\WordpressClient\Request;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface ClientInterface
 * @package Reactmore\WordpressClient\Request
 */
interface ClientInterface
{
    /**
     * @param string $uri
     * @return UriInterface
     */
    public function makeUri($uri);

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);
}
