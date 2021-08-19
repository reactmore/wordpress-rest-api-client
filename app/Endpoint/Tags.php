<?php

namespace Reactmore\WordpressClient\Endpoint;

/**
 * Class Posts
 * @package Reactmore\WordpressClient\Endpoint
 */
class Tags extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/wp/v2/tags';
    }
}
