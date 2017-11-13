<?php

namespace Pazulx\RESTBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundExceptionListener
{
    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {
            $data = [
                'message' => 'Not found',
            ];
            $response = new JsonResponse($data, Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }
}
