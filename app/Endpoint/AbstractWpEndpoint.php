<?php

namespace Reactmore\WordpressClient\Endpoint;

use Exception;
use GuzzleHttp\Psr7\Request;
use RuntimeException;
use Reactmore\WordpressClient\Request\GuzzleAdapter;
use Reactmore\WordpressClient\Wordpress;
use Reactmore\WordpressClient\Helpers\QueryString;

/**
 * Class AbstractWpEndpoint
 * @package Reactmore\WordpressClient\Endpoint
 */
abstract class AbstractWpEndpoint
{
    /**
     * @var Wordpress 
     */
    protected $client;

    /**
     * Users constructor.
     * @param WpClient $client
     */
    public function __construct(Wordpress $client)
    {
        $this->client = $client;
    }


    abstract protected function getEndpoint();

    /**
     * @param int $id
     * @param array $params - parameters that can be passed to GET
     *        e.g. for tags: https://developer.wordpress.org/rest-api/reference/tags/#arguments
     * @return array
     * @throws \RuntimeException
     */
    public function get($id = null, array $params = null)
    {
        try {

            $qs = new QueryString();

            $uri = $this->getEndpoint();
            $uri .= (is_null($id) ? '' : '/' . $id);
            $uri .= (is_null($params) ? '' : '?' . http_build_query($params));


            $request = new Request('GET', $uri, [
                'connect_timeout' => 10,
                'timeout' => 30
            ]);

            $response = $this->client->send($request);
            if (
                $response->hasHeader('Content-Type')
                && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json'
            ) {

                return [
                    'status' => 'success',
                    'code' => $response->getStatusCode(),
                    'data' => json_decode($response->getBody()->getContents(), true),

                ];
            }
        } catch (Exception $e) {
            return GuzzleAdapter::RequestHandleException($e);
        }
    }

    /**
     * @param int $id
     * @param array $params - parameters that can be passed to GET
     *        e.g. for tags: https://developer.wordpress.org/rest-api/reference/tags/#arguments
     * @return array
     * @throws \RuntimeException
     */
    public function getPaginations($id = null, array $params = null)
    {
        try {

            $qs = new QueryString();
            $qs->add('_fields', array('id', 'title', 'link', 'author', 'metadata', 'excerpt', 'date'));;
            $uri = $this->getEndpoint();
            $uri .= (is_null($id) ? '' : '/' . $id);
            $uri .= (is_null($params) ? '' : '?' . $qs->build() . '&' . http_build_query($params));


            $request = new Request('GET', $uri, [
                'connect_timeout' => 10,
                'timeout' => 30
            ]);

            $response = $this->client->send($request);
            if (
                $response->hasHeader('Content-Type')
                && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json'
            ) {
                return [
                    'status' => 'success',
                    'code' => $response->getStatusCode(),
                    'data' => json_decode($response->getBody()->getContents(), true),
                    'X-WP-TOTAL' => $response->getHeader('X-WP-Total')[0],
                    'X-WP-TOTAL-PAGE' =>  $response->getHeader('X-WP-TotalPages')[0],
                ];
            }
        } catch (Exception $e) {
            return GuzzleAdapter::RequestHandleException($e);
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \RuntimeException
     */
    public function save(array $data)
    {
        try {
            $url = $this->getEndpoint();


            if (isset($data['id'])) {
                $url .= '/' . $data['id'];
                unset($data['id']);
            }

            $request = new Request('POST', $url, ['Content-Type' => 'application/json'], json_encode($data));
            $response = $this->client->send($request);


            if (
                $response->hasHeader('Content-Type')
                && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json'
            ) {
                return [
                    'status' => 'success',
                    'code' => $response->getStatusCode(),
                    'data' => json_decode($response->getBody()->getContents(), true)
                ];
            }
        } catch (Exception $e) {
            return GuzzleAdapter::handleException($e);
        }
    }
}
