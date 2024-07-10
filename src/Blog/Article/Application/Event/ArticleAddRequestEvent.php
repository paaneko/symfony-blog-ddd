<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\EventDispatcher\Event;

class ArticleAddRequestEvent extends Event
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 15, max: 255, )]
    private string $title;

    #[Assert\NotBlank]
    #[Assert\Length(min: 250, max: 5000, )]
    private string $content;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $categoryId;

    #[Assert\Uuid]
    public ?string $sectionId;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private string $authorId;

    #[Assert\NotBlank]
    #[Assert\Uuid]
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
