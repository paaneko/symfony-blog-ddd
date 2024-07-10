<?php

declare(strict_types=1);

namespace App\SharedKernel\Test;

use Symfony\Component\Uid\Uuid;

/** @psalm-suppress UnusedClass */
class FakeUuid
{
    public static function generate(): string
    {
        return Uuid::v4()->toString();
    }
}
