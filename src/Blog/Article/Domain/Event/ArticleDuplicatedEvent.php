<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Event;

use App\SharedKernel\Event\DomainEventInterface;

class ArticleDuplicatedEvent implements DomainEventInterface
{
    public function __construct(
        private string $id,
        private string $title
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
