<?php

namespace Pazulx\JsonApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Pazulx\JsonApiBundle\Response\ApiResponse;
use JMS\Serializer\Serializer;

class ViewListener
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

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        if ($result instanceof ApiResponse) {
            $data = $this->serializer->serialize($result->getDto(), 'json');

            $response = $result->getResponse();
            $response->setContent($data);

            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }
}
