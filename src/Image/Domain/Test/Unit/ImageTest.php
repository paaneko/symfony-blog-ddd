<?php

declare(strict_types=1);

namespace App\Image\Domain\Test\Unit;

use App\Image\Domain\Test\Builder\ImageBuilder;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testCanSetUsed(): void
    {
        $image = (new ImageBuilder())->build();

        $image->setUsed();

        $this->assertEquals($image->isUsed(), true);
    }
}
