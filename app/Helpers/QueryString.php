<?php

namespace Reactmore\WordpressClient\Helpers;

class QueryString
{
    private $parts = array();

    public function add($key, $value)
    {
        if (empty($value)) return;
        if (is_array($value)) {
            foreach ($value as $v)
                $this->add($key, $v);
        } else {
            $this->parts[$key][] = $value;
        }
    }

    public function build($separator = '&', $equals = '=')
    {
        $queryString = array();

        $parts = array();
        foreach ($this->parts as $key => $value) {
            if (count($value) > 1)
                $parts[$key] = $value;
            else
                $parts[$key] = $value[0];
        }
        $query = http_build_query($parts);
        return preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '[]=', $query);;
    }

    public function __toString()
    {
        return $this->build();
    }
}
