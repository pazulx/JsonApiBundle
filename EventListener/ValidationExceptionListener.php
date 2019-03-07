<?php

namespace Pazulx\JsonApiBundle\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pazulx\JsonApiBundle\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionListener
{
    /**
     * {@inheritdoc}
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof ValidationException) {
            $data = [
                'message' => $exception->getMessage(),
                'errors' => $this->serializeViolations($exception->getErrors())
            ];
            $response = new JsonResponse($data, $exception->getCode());

            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }

    private function serializeViolations(ConstraintViolationList $violations)
    {
        $errors = [];

        foreach ($violations as $violation) {

            $path = $violation->getPropertyPath();
            $message = $violation->getMessage();

            if (empty($path)) {
                $errors[] = $message;
            } else {

                if (isset($errors[$path]) && !is_array($errors[$path])) {
                    if ($message == $errors[$path]) {
                        continue;
                    }
                    $errors[$path] = [$errors[$path]];
                }
                if (isset($errors[$path]) && is_array($errors[$path])) {
                    if (in_array($message, $errors[$path])) {
                        continue;
                    }
                    $errors[$path][] = $message;
                } else {
                    $errors[$path] = $message;
                }
            }
        }

        return $errors;
    }
}
