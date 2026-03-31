<?php

namespace App\Infra\Security;

use App\Infra\Mappers\AppRequestLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::RESPONSE, priority: -100)]
final readonly class ApiRequestLogListener
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $startTime = $request->server->get('REQUEST_TIME_FLOAT', microtime(true));
        $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        $log = new AppRequestLog();
        $log->setMethod($request->getMethod())
            ->setEndpoint($request->getPathInfo())
            ->setStatusCode($response->getStatusCode())
            ->setResponseTimeMs($responseTimeMs);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
