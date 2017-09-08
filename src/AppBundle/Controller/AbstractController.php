<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @param array $meta
     * @return JsonResponse
     */
    public function success(
        array $data = null,
        $status = JsonResponse::HTTP_OK,
        array $headers = [],
        array $context = [],
        array $meta = null
    ) : JsonResponse
    {

        $serializer = $this->get('custom_serializer');
        $json = $serializer->serialize(['success' => true, 'data' => $data, 'errors' => null, 'meta' => $meta], 'json', $context);
        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * @param $errors
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    public function badRequest(
        $errors,
        $status = JsonResponse::HTTP_BAD_REQUEST,
        array $headers,
        array $context = []
    ) : JsonResponse
    {
        $serializer = $this->get('custom_serializer');
        $json = $serializer->serialize(['success' => false, 'data' => null, 'errors' => $errors, 'meta' => null], 'json', $context);
        return new JsonResponse($json, $status, $headers, true);
    }
}