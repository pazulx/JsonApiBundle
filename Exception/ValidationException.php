<?php

namespace Pazulx\JsonApiBundle\Exception;

//use AppBundle\REST\Response\ValidationErrorResponse;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends \Exception implements ApiExceptionInterface
{
    /**
     * @var ValidationErrorResponse
     */
    private $errors;

    private $statusCode;

    /**
     * @param ConstraintViolationList $errors
     * @param int                     $statusCode
     */
    public function __construct($statusCode, ConstraintViolationList $errors = null)
    {
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    /**
     * getData.
     */
    public function getData()
    {
        return $this->errors;
    }

    /**
     * getData.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
