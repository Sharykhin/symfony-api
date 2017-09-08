<?php

namespace AppBundle\Controller;

use AppBundle\Normalizer\NullableObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Serializer;

/**
 * Class AbstractController
 * @package AppBundle\Controller
 */
abstract class AbstractController extends Controller
{
    /**
     * @param array|null $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    public function success(
        array $data = null,
        $status = JsonResponse::HTTP_OK,
        array $headers = [],
        array $context = []
    ) : JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $converter = null;
        if ($this->getParameter('camel_case_to_underscore_response') === true) {
            $converter = new CamelCaseToSnakeCaseNameConverter();
        }

        $normalizers = [new NullableObjectNormalizer(null, $converter)];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize(['success' => true, 'data' => $data], 'json', [
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context);
        return new JsonResponse($json, $status, $headers, true);
    }
}