<?php

namespace Pazulx\JsonApiBundle\Response;

use Pazulx\JsonApiBundle\DTO\DtoInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    private $dto;
    private $response;

    public function __construct(DtoInterface $dto, $status = 200)
    {
        $this->dto = $dto;

        $this->response = new Response('', $status);
        $this->response->headers->set('Content-Type', 'application/json');
    }

    public function getDto()
    {
        return $this->dto;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
