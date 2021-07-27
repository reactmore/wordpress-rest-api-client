<?php

namespace Reactmore\WordpressClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Reactmore\WordpressClient\Auth\AuthInterface;
use Reactmore\WordpressClient\Endpoint;
use Reactmore\WordpressClient\Request\ClientInterface;

/**
 * Class Wordpress
 */
class Wordpress
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var AuthInterface
     */
    private $credentials;

    /**
     * @var string
     */
    private $wordpressUrl;

    /**
     * @var array
     */
    private $endPoints = [];


    public function __construct(ClientInterface $httpClient, $wordpressUrl = '')
    {
        $this->httpClient = $httpClient;
        $this->wordpressUrl = $wordpressUrl;
    }

    /**
     * @param $wordpressUrl
     */
    public function setWordpressUrl($wordpressUrl)
    {
        $this->wordpressUrl = $wordpressUrl;
    }

    /**
     * @param AuthInterface $auth
     */
    public function setCredentials(AuthInterface $auth)
    {
        $this->credentials = $auth;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        if ($this->credentials) {
            $request = $this->credentials->addCredentials($request);
        }

        $request = $request->withUri(
            $this->httpClient->makeUri($this->wordpressUrl . $request->getUri())
        );

        return $this->httpClient->send($request);
    }
}
