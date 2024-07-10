<?php

namespace App\Blog\Article\Domain\Test\Builder;

use App\Blog\Article\Domain\Event\ArticleDuplicatedEvent;
use App\SharedKernel\Test\FakeUuid;

class ArticleDuplicatedEventBuilder
{
    private string $id;

    private string $title;

    public function __construct() {
        $this->id = FakeUuid::generate();
        $this->title = ('Lorem ipsum dolor sit amet');
    }

    public function build(): ArticleDuplicatedEvent
    {
        $event = new ArticleDuplicatedEvent(
            $this->id,
            $this->title,
        );

        return $event;
    }
}