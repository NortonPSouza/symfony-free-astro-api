<?php

namespace App\Infra\Security;

use App\App\Contracts\Gateway\TokenManagerInterface;
use App\Domain\Exceptions\UnauthorizedException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 10)]
final readonly class AuthenticateTokenListener
{
    public function __construct(
        private TokenManagerInterface $tokenManager
    ) {}

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->attributes->get('_authenticated')) {
            return;
        }
        try {
            $authorization = $request->headers->get('Authorization');
            if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
                throw new UnauthorizedException('Invalid authorization header');
            }
            $token = substr($authorization, 7);
            $userId = $this->tokenManager->validate($token);
            $request->attributes->set('user_id', $userId);
        } catch (UnauthorizedException $exception) {
            $event->setResponse(new JsonResponse($exception->getData(), $exception->getStatusCode()));
        }
    }
}
