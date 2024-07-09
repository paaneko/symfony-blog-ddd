<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\AddComment;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Command
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $articleId;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\Length(min: 25, max: 1000)]
    public string $message;

    public function __construct(string $articleId, string $name, string $email, string $message)
    {
        $this->articleId = $articleId;
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }
}
