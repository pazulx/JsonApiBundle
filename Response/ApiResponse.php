<?php

namespace Pazulx\JsonApiBundle\Response;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends Response
{
    private $dto;

    public function __construct($dto, $status = 200, $headers = array())
    {
        $this->dto = $dto;

        parent::__construct('', $status, $headers)
    }

    public function getDto()
    {
        return $this->dto;
    }
}
