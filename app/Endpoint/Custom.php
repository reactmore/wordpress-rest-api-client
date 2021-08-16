<?php

namespace Reactmore\WordpressClient\Endpoint;

/**
 * Class Posts
 * @package Reactmore\WordpressClient\Endpoint
 */
class Custom extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/custom/v1/all-posts';
    }
}
