<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\ValidateException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
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

        if ($exception instanceof ValidateException) {
            $errors = $exception->getErrors();

            if($this->container->getParameter('camel_case_to_underscore_response') === true) {
                $keys = array_keys($errors);
                $keys = array_map(function ($key) {
                    return from_camel_case($key);
                }, $keys);
                $errors = array_combine($keys, array_values($errors));
            }

            $data = [
                'success' => false,
                'data' => null,
                'errors' => $errors
            ];
            $json = $this->serializer->serialize($data, 'json', array_merge(array(
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            )), []);

            $response = new JsonResponse($json, JsonResponse::HTTP_BAD_REQUEST, [], true);
            $event->setResponse($response);
        }
    }
}