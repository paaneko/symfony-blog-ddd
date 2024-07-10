<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Test\Builder;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\SharedKernel\Test\FakeUuid;

class ArticleCreatedEventBuilder
{
    private string $id;

    private string $title;

    private string $mainImageId;

    public function __construct()
    {
        $this->id = FakeUuid::generate();
        $this->title = 'Lorem ipsum dolor sit amet';
        $this->mainImageId = FakeUuid::generate();
    }

    public function build(): ArticleCreatedEvent
    {
        $event = new ArticleCreatedEvent(
            $this->id,
            $this->title,
            $this->mainImageId
        );

        return $event;
    }
}
