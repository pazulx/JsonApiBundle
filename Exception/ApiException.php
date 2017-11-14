<?php

namespace Pazulx\RESTBundle\Exception;

//use AppBundle\REST\Response\ValidationErrorResponse;

class ApiException extends \Exception implements ApiExceptionInterface
{
    /**
     * @var array
     */
    private $data;

    private $statusCode;

    /**
     * @param array $data
     * @param int   $statusCode
     */
    public function __construct($statusCode, array $data)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    /**
     * getData.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * getData.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
