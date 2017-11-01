<?php

if (!function_exists('from_camel_case')) {
    function from_camel_case(string $input) :  string {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}

if (! function_exists('request_intersect')) {
    function request_intersect(array $params, array $fields) : array {
        $keys = array_flip(array_intersect($fields, array_keys($params)));
        return array_intersect_key($params, $keys);
    }
}
