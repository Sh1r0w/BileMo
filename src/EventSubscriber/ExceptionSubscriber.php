<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * The function handles kernel exceptions in PHP by returning a JSON response with the status code
     * and message of the exception.
     * 
     * @param ExceptionEvent event The `onKernelException` function is an event listener in Symfony
     * that is triggered when an exception occurs during the handling of a request. The ``
     * parameter of type `ExceptionEvent` contains information about the exception that occurred.
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data));
        } else {
            $data = [
                'status' => 500,
                'message' => $exception->getMessage()
            ];

            $event->setResponse(new JsonResponse($data));
        }
    }

    /**
     * The function `getSubscribedEvents` returns an array with the subscribed event
     * `KernelEvents::EXCEPTION` mapped to the method `onKernelException`.
     * 
     * @return array An array containing the event `KernelEvents::EXCEPTION` subscribed to the method
     * `onKernelException`.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
