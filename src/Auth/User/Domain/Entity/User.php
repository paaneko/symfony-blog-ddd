<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Entity;

use App\Auth\User\Domain\ValueObject\Email;
use App\Auth\User\Domain\ValueObject\Name;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 255)]
    private Id $id;

    #[ORM\Embedded(columnPrefix: false)]
    private Name $name;

    #[ORM\Embedded(columnPrefix: false)]
    private Email $email;

    public function __construct(Id $id, Name $name, Email $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): Id
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getName(): Name
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getEmail(): Email
    {
        return $this->email;
    }
}
