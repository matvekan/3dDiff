<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ApiExceptionSubscriber
{
    public function __invoke(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        $exception = $event->getThrowable();
        while ($exception instanceof HandlerFailedException && null !== $exception->getPrevious()) {
            $exception = $exception->getPrevious();
        }

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $errors = null;

        if ($exception instanceof ValidationFailedException) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $errors = [];
            foreach ($exception->getViolations() as $violation) {
                $errors[] = [
                    'field' => (string) $violation->getPropertyPath(),
                    'message' => (string) $violation->getMessage(),
                ];
            }
        }

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        $message = trim($exception->getMessage());
        if ('' === $message) {
            $message = Response::HTTP_INTERNAL_SERVER_ERROR === $statusCode ? 'Internal server error' : 'Request failed';
        }

        $payload = ['error' => $message];
        if (null !== $errors) {
            $payload['violations'] = $errors;
        }

        $event->setResponse(new JsonResponse($payload, $statusCode));
    }
}
