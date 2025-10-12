<?php

declare(strict_types=1);

namespace App\Messenger\Middleware;

use App\Messenger\Stamp\UniqueIdStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

readonly class AuditMiddleware implements MiddlewareInterface
{
    public function __construct(
        #[Autowire(service: 'monolog.logger.messenger_audit')]
        private LoggerInterface $logger,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(UniqueIdStamp::class)) {
            $envelope = $envelope->with(new UniqueIdStamp());
        }

        $envelope = $stack->next()->handle($envelope, $stack);

        $this->logMessageStatus($envelope);

        return $envelope;
    }

    private function logMessageStatus(Envelope $envelope): void
    {
        $stamp = $envelope->last(UniqueIdStamp::class);
        $context = [
            'id' => $stamp->getUniqueId(),
            'class' => get_class($envelope->getMessage()),
        ];

        if (null !== $envelope->last(SentStamp::class)) {
            $this->logger->info('[{id}] Sent {class}', $context);
        } elseif (null !== $envelope->last(ReceivedStamp::class) ) {
            $this->logger->info('[{id}] Received {class}', $context);
        } else {
            $this->logger->info('[{id}] Handling sync {class}', $context);
        }
    }
}