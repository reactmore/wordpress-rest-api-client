<?php

namespace Reactmore\WordpressClient\Request;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception\ConnectException;

class GuzzleAdapter implements ClientInterface
{
    /**
     * @var Client
     */
    protected $guzzle;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->guzzle = $client ?: new Client();
    }

    public function makeUri($uri)
    {
        return new Uri($uri);
    }

    public function send(RequestInterface $request)
    {
        return $this->guzzle->send($request);
    }
}
