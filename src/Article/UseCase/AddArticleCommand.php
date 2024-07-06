<?php

declare(strict_types=1);

namespace App\Article\UseCase;

use Symfony\Component\Validator\Constraints as Rule;

readonly class AddArticleCommand
{
    #[Rule\NotBlank(message: 'Title should not be blank.')]
    #[Rule\Length(
        min: 3,
        max: 60,
        minMessage: 'Title must be at least {{ limit }} characters long.',
        maxMessage: 'Title cannot be longer than {{ limit }} characters.'
    )]
    public string $title;

    #[Rule\NotBlank(message: 'Content is required.')]
    #[Rule\Length(
        min: 10,
        max: 1000,
        minMessage: 'Content must be at least {{ limit }} characters long.',
        maxMessage: 'Content cannot be longer than {{ limit }} characters.'
    )]
    public string $content;

    #[Rule\NotBlank(message: 'Image uuid required.')]
    #[Rule\Uuid]
    public string $imageId;

    #[Rule\NotBlank(message: 'Image name is required.')]
    #[Rule\Length(
        max: 255,
        maxMessage: 'Image name cannot be longer than {{ limit }} characters.'
    )]
    public string $imageName;

    public function __construct(
        string $title,
        string $content,
        string $imageId,
        string $imageName
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->imageId = $imageId;
        $this->imageName = $imageName;
    }
}
