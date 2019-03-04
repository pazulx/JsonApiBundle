<?php

namespace Pazulx\JsonApiBundle\Exception;

//use AppBundle\REST\Response\ValidationErrorResponse;

class ApiException extends \Exception
{
    const HTTP_BAD_REQUEST = 400;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    public function __construct($message = '', $statusCode = null)
    {
        $statusCode = $statusCode ?? ApiException::HTTP_BAD_REQUEST;

        parent::__construct($message, $statusCode);
    }
}
