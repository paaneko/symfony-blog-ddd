<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Listener;

use App\SharedKernel\Domain\Exception\DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationList;

final readonly class ExceptionListener
{
    public function __construct(
        private string $env,
        private LoggerInterface $logger
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ('dev' === $this->env) {
            return;
        }
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $this->logger->notice($exception->getMessage());

            $event->setResponse(
                new JsonResponse(
                    ['message' => $this->formatValidationErrors($exception->getViolations())],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    []
                )
            );
        } elseif ($exception instanceof HandlerFailedException) {
            if ($exception->getPrevious() instanceof DomainException) {
                /** @var $domainException DomainException */
                $domainException = $exception->getPrevious();
                $this->logger->notice($exception->getMessage(), [
                    'file' => $exception->getFile() . ':' . $exception->getLine(),
                    'message' => $exception->getMessage(),
                ]);
                $event->setResponse(
                    new JsonResponse(
                        ['message' => $domainException->getMessage()],
                        Response::HTTP_BAD_REQUEST
                    )
                );
            }
        } elseif ($exception instanceof DomainException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    Response::HTTP_BAD_REQUEST
                )
            );
        } else {
            $this->logger->error($exception->getMessage(), [
                'file' => $exception->getFile() . ':' . $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'message' => $exception->getMessage(),
            ]);
            $data = [
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            ];
            $event->setResponse(new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    private function formatValidationErrors(ConstraintViolationList $violationList): array
    {
        $errors = [];
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
