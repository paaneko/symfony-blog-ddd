<?php

declare(strict_types=1);

namespace App\Blog\Category\Application\UseCase\Add;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $slug;

    public function __construct(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
    }
}
