<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
final class ArticleViews
{
    #[ORM\Column(type: 'integer')]
    private int $value;

    public static function init(): self
    {
        $object = new self();

        $object->value = 0;

        return $object;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function increment(): self
    {
        $object = clone $this;

        ++$object->value;

        return $object;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): int
    {
        return $this->value;
    }
}
