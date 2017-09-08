<?php

namespace AppBundle;

use AppBundle\Normalizer\NullableObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ResponseTrait
 * @package AppBundle
 */
trait ResponseTrait
{
    /**
     * @param array|null $data
     * @param array $context
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function success(
        array $data = null,
        array $context = [],
        $status = JsonResponse::HTTP_OK,
        array $headers = []
    ) : JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new NullableObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize(['success' => true, 'data' => $data], 'json', [
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context);
        return new JsonResponse($json, $status, $headers, true);
    }
}
