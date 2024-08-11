<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\JoinByEmail;

use App\SharedKernel\Domain\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class JoinByEmailCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 50)]
    public string $password;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
