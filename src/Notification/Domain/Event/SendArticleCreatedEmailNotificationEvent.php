<?php

namespace App\Notification\Domain\Event;

use App\SharedKernel\Domain\Event\DomainEventInterface;

final readonly class SendArticleCreatedEmailNotificationEvent implements DomainEventInterface
{
    public function __construct(
        private string $followerEmail,
        private string $followeeName,
        private string $followerName,
        private string $articleTitle
    ) {
    }

    public function getFollowerEmail(): string
    {
        return $this->followerEmail;
    }

    public function getFolloweeName(): string
    {
        return $this->followeeName;
    }

    public function getFollowerName(): string
    {
        return $this->followerName;
    }

    public function getArticleTitle(): string
    {
        return $this->articleTitle;
    }
}