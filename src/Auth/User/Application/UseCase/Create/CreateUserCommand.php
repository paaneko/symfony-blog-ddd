<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\Create;

use App\SharedKernel\Domain\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 225)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}
