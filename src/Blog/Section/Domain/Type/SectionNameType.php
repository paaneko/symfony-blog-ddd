<?php

namespace App\Blog\Section\Domain\Type;

use App\Blog\Section\Domain\ValueObject\SectionName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class SectionNameType extends StringType
{
    public const string NAME = 'section_name';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof SectionName ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?SectionName
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new SectionName((string) $value) : null;
    }
}