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
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @param array $meta
     * @return JsonResponse
     */
    public function success(
        $data = null,
        $status = JsonResponse::HTTP_OK,
        array $headers = [],
        array $context = ['groups' => ['list']],
        array $meta = null
    ) : JsonResponse
    {

        $context = $this->extendContext($context);
        return $this->json(['success' => true, 'data' => $data, 'errors' => null, 'meta' => $meta], $status, $headers, $context);
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
        $context = $this->extendContext($context);
        return $this->json(['success' => true, 'data' => null, 'errors' => $errors, 'meta' => null], $status, $headers, $context);
    }

    /**
     * @param string $error
     * @return JsonResponse
     */
    public function notFound(string $error = 'resource was not fount') : JsonResponse
    {
        return $this->json(['success' => true, 'data' => null, 'errors' => $error, 'meta' => null], JsonResponse::HTTP_NOT_FOUND, [], []);
    }

    /**
     * @param array $context
     * @return array
     */
    private function extendContext(array $context) : array
    {
        if ($this->isGranted('ROLE_ADMIN', $this->getUser())) {
            array_push($context['groups'], 'role_admin');
        }

        return $context;
    }
}
