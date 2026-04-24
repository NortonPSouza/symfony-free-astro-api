<?php

namespace App\Infra\Security;

use App\Domain\Exceptions\GenericException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof GenericException) {
            $event->setResponse(
                new JsonResponse($exception->getData(), $exception->getStatusCode())
            );
            return;
        }
        if ($exception instanceof \DateMalformedStringException) {
            $event->setResponse(
                new JsonResponse(['exception' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST)
            );
            return;
        }
        $event->setResponse(
            new JsonResponse(['exception' => 'Internal server error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
        );
    }
}
