<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class AccessDeniedListener implements EventSubscriberInterface
{
    /** @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>> */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onKernelException', 2]];
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof AccessDeniedException) {
            $event->allowCustomResponseCode();
            $event->setResponse(match ($event->getRequest()->getPathInfo()) {
                '/check' => new JsonResponse(false, 200),
                default => new JsonResponse(
                    [
                        'code' => $exception->getCode(),
                        'message' => $exception->getMessage()
                    ],
                    $exception->getCode()
                )
            });
        }
    }
}
