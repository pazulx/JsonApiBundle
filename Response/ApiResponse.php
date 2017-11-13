<?php

namespace Pazulx\RESTBundle\Response;

use Pazulx\RESTBundle\DTO\DtoInterface;

class ApiResponse
{
    private $statusCode;
    private $dto;

    public function __construct($statusCode, DtoInterface $dto)
    {
        $this->statusCode = $statusCode;
        $this->dto = $dto;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getDto()
    {
        return $this->dto;
    }
}
