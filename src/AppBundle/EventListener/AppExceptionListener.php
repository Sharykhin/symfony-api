<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\ConstraintValidateException;
use AppBundle\Exception\FormValidateException;
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
    }
}