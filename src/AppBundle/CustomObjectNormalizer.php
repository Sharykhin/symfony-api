<?php

namespace AppBundle;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class CustomObjectNormalizer extends ObjectNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);
        
        return array_filter($data, function ($value) {
            return null !== $value && '' !== $value;
        });
    }
}