<?php

namespace Pazulx\JsonApiBundle\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pazulx\JsonApiBundle\Exception\ApiException;

class ApiExceptionListener
{
    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        //dump(['ApiExceptionListener' => $exception]);

        if ($exception instanceof ApiException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], $exception->getCode());

            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }
}
