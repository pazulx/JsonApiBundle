<?php

namespace Pazulx\JsonApiBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Pazulx\JsonApiBundle\Response\ApiResponse;
use JMS\Serializer\Serializer;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

//use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

class PreDeserializeListener //implements EventSubscriberInterface
{
    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        //dump($event);

        $type = $event->getType();
        $data = $event->getData();

        $metadata = $event->getContext()->getMetadataFactory()->getMetadataForClass($type['name']);

        //dump($metadata);
    }
}
