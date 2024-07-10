<?php

namespace App\Image\Domain\Test\Builder;

use App\Image\Domain\Entity\Id;
use App\Image\Domain\Entity\Image;
use App\Image\Domain\ValueObject\Name;

class ImageBuilder
{
    private Id $id;

    private Name $name;

    private bool $isUsed;

    public function __construct()
    {
        $this->id = Id::generate();
        $this->name = new Name('Lorem ipsum dolor sit amet');
        $this->isUsed = false;
    }

    public function used(): self
    {
        $clone = clone $this;
        $clone->isUsed = true;
        return $clone;
    }

    public function build(): Image
    {
        $image = new Image(
            $this->id,
            $this->name,
            $this->isUsed
        );

        return $image;
    }
}