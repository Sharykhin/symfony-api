<?php

namespace AppBundle\Service;

use AppBundle\Normalizer\NullableObjectNormalizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CustomSerializer
 * @package AppBundle\Service
 */
class CustomSerializer implements SerializerInterface
{
    /** @var ContainerInterface $container */
    protected $container;

    /**
     * CustomSerializer constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    /**
     * @param mixed $data
     * @param string $format
     * @param array $context
     * @return string|\Symfony\Component\Serializer\Encoder\scalar
     */
    public function serialize($data, $format, array $context = array())
    {
        $encoders = [new JsonEncoder()];
        $converter = null;
        if ($this->container->getParameter('output_camel_case_to_underscore') === true) {
            $converter = new CamelCaseToSnakeCaseNameConverter();
        }

        if ($this->container->getParameter('output_hide_null') === true) {
            $normalizers = [new NullableObjectNormalizer(null, $converter)];
        } else {
            $normalizers = [new ObjectNormalizer(null, $converter)];
        }
        $serializer = new Serializer($normalizers, $encoders);

        if ($format === 'json') {
            return $serializer->serialize($data, $format, array_merge(array(
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ), $context));
        }

        return $serializer->serialize($data, $format, $context);

    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string $format
     * @param array $context
     * @return object
     */
    public function deserialize($data, $type, $format, array $context = array())
    {
        /** @var SerializerInterface $seriazlier $seriazlier */
        $seriazlier = $this->container->get('serializer');
        return $seriazlier->deserialize($data, $type, $format, $context);
    }
}
