<?php

namespace Pazulx\JsonApiBundle\Exception;

interface ApiExceptionInterface
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode();

    /**
     * Returns error data.
     *
     * @return array
     */
    public function getData();
}
