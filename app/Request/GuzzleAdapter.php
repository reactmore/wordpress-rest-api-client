<?php

namespace Reactmore\WordpressClient\Request;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception\ConnectException;
use Reactmore\WordpressClient\Helpers\ResponseFormatter;

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

    /**
     * {@inheritdoc}
     */
    public function makeUri($uri)
    {
        return new Uri($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestInterface $request)
    {
        return $this->guzzle->send($request);
    }

    public static function handleException($exception)
    {
        if ($exception instanceof ConnectException) {
            return ResponseFormatter::formatResponse([
                'error' => 'Connection Timeout Error. Please check your internet connection and try again'
            ], 408, 'failed');
        } else {
            return ResponseFormatter::formatResponse([
                'error' => $exception->getMessage()
            ], 400, 'failed');
        }
    }
}
