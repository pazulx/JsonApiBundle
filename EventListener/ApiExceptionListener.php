<?php

namespace Pazulx\JsonApiBundle\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Pazulx\JsonApiBundle\Exception\ApiExceptionInterface;

class ApiExceptionListener
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof ApiExceptionInterface) {
            $data = $this->serializer->serialize($exception->getData(), 'json');
            $response = new Response($data, $exception->getStatusCode(), ['Content-Type' => 'application/json']);

            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }
}
