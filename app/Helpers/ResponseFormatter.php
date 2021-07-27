<?php

namespace Reactmore\WordpressClient\Helpers;


/**
 * Class ResponseFormatter
 * @package Reactmore\WordpressClient\Http\Client
 */
class ResponseFormatter
{
    public static function formatResponse($data, $code = 200, $status = 'success')
    {
        return [
            'status' => $status,
            'code' => $code,
            'data' => $data
        ];
    }
}
