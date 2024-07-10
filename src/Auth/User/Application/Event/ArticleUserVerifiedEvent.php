<?php

declare(strict_types=1);

namespace App\Auth\User\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ArticleUserVerifiedEvent extends Event
{
    private string $title;

    private string $content;

    private string $categoryId;

    private ?string $sectionId;

    private string $authorId;

    private string $imageId;

    public function __construct(
        string $title,
        string $content,
        string $categoryId,
        ?string $sectionId,
        string $authorId,
        string $imageId,
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->categoryId = $categoryId;
        $this->sectionId = $sectionId;
        $this->authorId = $authorId;
        $this->imageId = $imageId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getSectionId(): ?string
    {
        return $this->sectionId;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getImageId(): string
    {
        return $this->imageId;
    }
}
