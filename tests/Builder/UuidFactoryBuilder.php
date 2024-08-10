<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final class UuidFactoryBuilder
{
    private string $uuidVersion;

    public function __construct()
    {
        $this->uuidVersion = UuidV4::class;
    }

    public function version(Uuid $version): self
    {
        $clone = clone $this;
        $clone->uuidVersion = $version::class;

        return $clone;
    }

    public function build(): UuidFactory
    {
        return new UuidFactory($this->uuidVersion);
    }
}
