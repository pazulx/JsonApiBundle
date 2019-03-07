<?php

namespace Pazulx\JsonApiBundle\Exception;

//use AppBundle\REST\Response\ValidationErrorResponse;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends \Exception
{
    const DEFAULT_VALIDATION_ERROR_MESSAGE = 'Validation error.';

    protected $errors;

    public function __construct(
        ConstraintViolationList $errors,
        $message = null,
        $statusCode = null
    ) {
        $statusCode = $statusCode ?? ApiException::HTTP_BAD_REQUEST;
        $message = $message ?? self::DEFAULT_VALIDATION_ERROR_MESSAGE;

        $this->errors =$errors;

        parent::__construct($message, $statusCode);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
