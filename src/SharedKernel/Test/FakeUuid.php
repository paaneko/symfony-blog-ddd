<?php

namespace App\SharedKernel\Test;

use Symfony\Component\Uid\Uuid;

class FakeUuid
{
    public static function generate(): string
    {
        return Uuid::v4()->toString();
    }
}