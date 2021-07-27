<?php

namespace Tests\Auth;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Reactmore\WordpressClient\Auth\WpBasicAuth;

class WpBasicAuthTest extends TestCase
{
    public function testAddCredentialsReturnsProperAuthorizationHeader()
    {
        $auth = new WpBasicAuth('more', 'more');
        $request = new Request('GET', '/users');

        $newRequest = $auth->addCredentials($request);

        $this->assertInstanceOf(Request::class, $newRequest);

        $this->assertEquals(
            ['Basic ' . base64_encode('more:more')],
            $newRequest->getHeader('Authorization')
        );
    }
}
