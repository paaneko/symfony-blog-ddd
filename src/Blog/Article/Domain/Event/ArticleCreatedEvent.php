<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Event;

use App\Blog\Article\Domain\ValueObject\MainImageId;
use App\SharedKernel\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ArticleCreatedEvent extends Event implements DomainEventInterface
{
    public function __construct(
        private string $id,
        private string $title,
        private string $mainImageId,
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

    public function getMainImageId(): string
    {
        return $this->mainImageId;
    }
}
