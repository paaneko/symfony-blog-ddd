<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Image\Domain\Entity\Image;
use App\Image\Domain\ValueObject\ImageId;
use App\Image\Domain\ValueObject\ImageName;

class ImageBuilder
{
    private ImageId $id;

    private ImageName $name;

    private bool $isUsed;

    public function __construct()
    {
        $this->id = ImageId::generate();
        $this->name = new ImageName('Lorem ipsum dolor sit amet');
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
