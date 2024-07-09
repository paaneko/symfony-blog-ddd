<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Create;

readonly class Command
{
    public string $title;

    public string $content;

    public string $categoryId;

    public ?string $sectionId;

    public string $authorId;

    public string $imageId;

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
}
