<?php

namespace Pazulx\JsonApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;

class SecurityExceptionListener
{
    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof InsufficientAuthenticationException) {
            $message = 'HTTP_FORBIDDEN';
            $code = Response::HTTP_FORBIDDEN;
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $message = 'HTTP_UNAUTHORIZED';
            $code = Response::HTTP_UNAUTHORIZED;
        } else {
            return;
        }

        $data = [
            'message' => $message,
        ];
        $response = new JsonResponse($data, $code);

        $event->setResponse($response);
    }
}
