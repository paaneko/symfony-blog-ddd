<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Test\Builder;

use App\Blog\Article\Application\Event\ArticleAddRequestEvent;
use App\SharedKernel\Test\FakeUuid;

class ArticleAddRequestEventBuilder
{
    private string $title;

    private string $content;

    public string $categoryId;

    public ?string $sectionId;

    private string $authorId;

    private string $imageId;

    public function __construct()
    {
        $this->title = 'Lorem ipsum dolor sit amet';
        $this->content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in.';
        $this->categoryId = FakeUuid::generate();
        $this->sectionId = FakeUuid::generate();
        $this->authorId = FakeUuid::generate();
        $this->imageId = FakeUuid::generate();
    }

    public function build(): ArticleAddRequestEvent
    {
        $event = new ArticleAddRequestEvent(
            $this->title,
            $this->content,
            $this->categoryId,
            $this->sectionId,
            $this->authorId,
            $this->imageId
        );

        return $event;
    }
}
