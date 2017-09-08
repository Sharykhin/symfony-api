<?php

namespace AppBundle\Exception;

use Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class FormValidateException
 * @package AppBundle\Exception
 */
class FormValidateException extends Exception
{
    /** @var FormErrorIterator $errors */
    protected $errors;

    /**
     * ConstraintValidateException constructor.
     * @param FormErrorIterator $errors
     * @param int $code
     */
    public function __construct(FormErrorIterator $errors, $code = 400)
    {
        $this->errors = $errors;
        parent::__construct("", $code, null);
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        $errors = [];
        /** @var FormError $error */
        foreach ($this->errors as $error) {
            /** @var ConstraintViolation $violation */
            $violation = $error->getCause();
            $propertyPath = substr($violation->getPropertyPath(), mb_strlen('data.'));
            $errors[$propertyPath] = $violation->getMessage();
        }

        return $errors;
    }
}
