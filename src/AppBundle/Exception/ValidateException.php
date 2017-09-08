<?php

namespace AppBundle\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ValidateException
 * @package AppBundle\Exception
 */
class ValidateException extends Exception
{
    /** @var ConstraintViolationList $errors */
    protected $errors;

    /**
     * ValidateException constructor.
     * @param ConstraintViolationList $errors
     * @param int $code
     */
    public function __construct(ConstraintViolationList $errors, $code = 400)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        $errors = [];
        /** @var ConstraintViolation $error */
        foreach ($this->errors as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errors;
    }
}
