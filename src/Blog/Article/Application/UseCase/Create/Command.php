<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 15, max: 255, )]
    public string $title;

    #[Assert\NotBlank]
    #[Assert\Length(min: 250, max: 5000, )]
    public string $content;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $categoryId;

    #[Assert\Uuid]
    public ?string $sectionId;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $authorId;

    #[Assert\NotBlank]
    #[Assert\Uuid]
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
