<?php

namespace Pazulx\JsonApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class MethodNotAllowedExceptionListener
{
    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof MethodNotAllowedHttpException) {
            $data = [
                'message' => $exception->getMessage(),
            ];
            $response = new JsonResponse($data, Response::HTTP_METHOD_NOT_ALLOWED);
            $event->setResponse($response);
        }
    }
}
