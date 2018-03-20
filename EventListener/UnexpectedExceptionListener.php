<?php

namespace Pazulx\JsonApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

class UnexpectedExceptionListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        //dump($exception);

        $data = [
            'message' => 'Unexpected error',
        ];
        $response = new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);

        $event->setResponse($response);

        $this->logger->critical('Unexpected error', [
            'exception' => $exception->__toString(),
        ]);
    }
}
