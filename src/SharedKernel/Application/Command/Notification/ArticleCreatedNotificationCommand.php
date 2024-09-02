<?php

namespace App\SharedKernel\Application\Command\Notification;

use App\SharedKernel\Domain\Bus\CommandInterface;

readonly class ArticleCreatedNotificationCommand implements CommandInterface
{
    public string $articleTitle;

    public string $followeeId;

    public string $followerId;

    public function __construct(
        string $articleTitle,
        string $followeeId,
        string $followerId
    ) {
        $this->articleTitle = $articleTitle;
        $this->followeeId = $followeeId;
        $this->followerId = $followerId;
    }
}