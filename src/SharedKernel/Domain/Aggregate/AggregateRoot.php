<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Aggregate;

use App\SharedKernel\Domain\Event\DomainEventInterface;

abstract class AggregateRoot
{
    /** @var array<int, DomainEventInterface> */
    protected array $domainEvents;

    public function recordDomainEvent(DomainEventInterface $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return array<int, DomainEventInterface> */
    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }
}
