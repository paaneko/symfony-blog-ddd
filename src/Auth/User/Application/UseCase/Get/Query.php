<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\Get;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Query
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
