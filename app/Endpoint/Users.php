<?php

namespace Reactmore\WordpressClient\Endpoint;

/**
 * Class Users
 * @package Reactmore\WordpressClient\Endpoint
 */
class Users extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/wp/v2/users';
    }
}
