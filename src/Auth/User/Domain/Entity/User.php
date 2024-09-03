<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Entity;

use App\Auth\User\Domain\Event\RequestJoinByEmailEvent;
use App\Auth\User\Domain\Type\UserEmailType;
use App\Auth\User\Domain\Type\UserIdType;
use App\Auth\User\Domain\Type\UserNameType;
use App\Auth\User\Domain\Type\UserStatusType;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;
use App\Auth\User\Domain\ValueObject\UserStatus;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table('users')]
class User extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: UserIdType::NAME, length: 255)]
    private UserId $id;

    #[ORM\Column(type: UserNameType::NAME, length: 255)]
    private UserName $name;

    #[ORM\Column(type: UserEmailType::NAME, length: 255)]
    private UserEmail $email;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $passwordHash = null;

    #[ORM\Embedded(class: Token::class)]
    private ?Token $joinConfirmToken;

    #[ORM\Column(type: UserStatusType::NAME, length: 255)]
    private UserStatus $status;

    private \DateTimeImmutable $createdAt;

    private function __construct(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserStatus $status,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public static function requestJoinByEmail(
        UserId $id,
        UserName $name,
        UserEmail $email,
        string $passwordHash,
        Token $token,
        \DateTimeImmutable $createdAt
    ): self {
        $user = new User($id, $name, $email, UserStatus::inactive(), $createdAt);

        $user->joinConfirmToken = $token;
        $user->passwordHash = $passwordHash;

        $user->recordDomainEvent(new RequestJoinByEmailEvent(
            $user->getEmail()->getValue(),
            $user->getJoinConfirmToken()?->getValue()
        ));

        return $user;
    }

    public function confirmJoin(
        string $confirmTokenValue,
        \DateTimeImmutable $now
    ): void {
        $this->joinConfirmToken->validate($confirmTokenValue, $now);
        $this->status = UserStatus::active();
        $this->joinConfirmToken = null;
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): UserId
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getName(): UserName
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PostLoad]
    public function loadTokens(): void
    {
        if (null === $this->joinConfirmToken->getValue()) {
            $this->joinConfirmToken = null;
        }
    }
}
