<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\AuthInvalidCredentials;
use AppBundle\Exception\ConstraintValidateException;
use AppBundle\Exception\FormValidateException;
use Firebase\JWT\ExpiredException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AppExceptionListener
 * @package AppBundle\EventListener
 */
class AppExceptionListener
{
    /** @var SerializerInterface $serializer */
    protected $serializer;

    /** @var ContainerInterface $container */
    protected $container;

    /**
     * AppExceptionListener constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer,
        ContainerInterface $container
    )
    {
        $this->serializer = $serializer;
        $this->container = $container;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof ConstraintValidateException || $exception instanceof FormValidateException) {
            $errors = $exception->getErrors();

            if($this->container->getParameter('output_camel_case_to_underscore') === true) {
                $keys = array_keys($errors);
                $keys = array_map(function ($key) {
                    return from_camel_case($key);
                }, $keys);
                $errors = array_combine($keys, array_values($errors));
            }

            $data = [
                'success' => false,
                'data' => null,
                'errors' => $errors,
                'meta' => null
            ];
            $json = $this->serializer->serialize($data, 'json');

            $response = new JsonResponse($json, JsonResponse::HTTP_BAD_REQUEST, [], true);
            $event->setResponse($response);
        }

        if ($exception instanceof AuthInvalidCredentials) {
            $data = [
                'success' => false,
                'data' => null,
                'errors' => 'Invalid Credentials',
                'meta' => null
            ];
            $json = $this->serializer->serialize($data, 'json');
            $response = new JsonResponse($json, JsonResponse::HTTP_UNAUTHORIZED, [], true);
            $event->setResponse($response);
        }

        if ($exception instanceof ExpiredException) {
            $data = [
                'success' => false,
                'data' => null,
                'errors' => 'Token has been expired',
                'meta' => null
            ];
            $json = $this->serializer->serialize($data, 'json');
            $response = new JsonResponse($json, JsonResponse::HTTP_UNAUTHORIZED, [], true);
            $event->setResponse($response);
        }

        if ($exception instanceof InsufficientAuthenticationException) {
            $data = [
                'success' => false,
                'data' => null,
                'errors' => $exception->getMessage(),
                'meta' => null
            ];
            $json = $this->serializer->serialize($data, 'json');
            $response = new JsonResponse($json, JsonResponse::HTTP_UNAUTHORIZED, [], true);
            $event->setResponse($response);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $data = [
                'success' => false,
                'data' => null,
                'errors' => $exception->getMessage(),
                'meta' => null
            ];
            $json = $this->serializer->serialize($data, 'json');
            $response = new JsonResponse($json, JsonResponse::HTTP_FORBIDDEN, [], true);
            $event->setResponse($response);
        }
    }
}