<?php

namespace AppBundle\Contract\Service\Token;

/**
 * Interface IJWTManager
 * @package AppBundle\Contract\Service\Token
 */
interface IJWTManager
{
    /**
     * @param string $jwt
     * @param array $allowed_algs
     * @return mixed
     */
    public function decode(string $jwt, array $allowed_algs = []);


    /**
     * @param array $payload
     * @param string $alg
     * @param null $keyId
     * @param null $head
     * @return string
     */
    public function encode(array $payload = [], $alg = 'HS256', $keyId = null, $head = null) : string;
}