<?php

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Reactmore\WordpressClient\Endpoint\AbstractWpEndpoint;
use Reactmore\WordpressClient\Wordpress;
use Hamcrest\Matchers as m;


class FakeEndpoint extends AbstractWpEndpoint
{
    public function getEndpoint()
    {
        return '/foo';
    }
}

class AbstractWpEndpointTest extends TestCase
{
    protected function setUp(): void
    {
        $this->client = Mockery::mock(Wordpress::class);
    }

    public function testGetRequestToEndpointURL()
    {
        $request = new Request('GET', '/foo/55');
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

        $this->client->shouldReceive('send')->with(m::equalTo($request))->andReturn($response);

        $data = (new FakeEndpoint($this->client))->get(55);

        $this->assertEquals(['foo' => 'bar'], $data);
    }

    public function testGetRequestWithoutID()
    {
        $request = new Request('GET', '/foo');
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

        $this->client->shouldReceive('send')->with(m::equalTo($request))->andReturn($response);

        $data = (new FakeEndpoint($this->client))->get();

        $this->assertEquals(['foo' => 'bar'], $data);
    }

    public function testGetRequestWithParameters()
    {
        $request = new Request('GET', '/foo?bar=baz');
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

        $this->client->shouldReceive('send')->with(m::equalTo($request))->andReturn($response);

        $data = (new FakeEndpoint($this->client))->get(null, ['bar' => 'baz']);

        $this->assertEquals(['foo' => 'bar'], $data);
    }

    public function testPostRequestToEndpointURL()
    {
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}');

        $this->client->shouldReceive('send')->with(\Mockery::type(Request::class))->andReturn($response);

        $data = (new FakeEndpoint($this->client))->save(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $data);
    }

    public function testPostRequestHandlesUnicodeData()
    {
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"first": "Iv??n", "last": "Pe??a"}');

        $this->client->shouldReceive('send')->with(\Mockery::type(Request::class))->andReturn($response);

        $data = (new FakeEndpoint($this->client))->save(['foo' => 'bar']);

        $this->assertEquals(['first' => 'Iv??n', 'last' => 'Pe??a'], $data);
    }
}
