<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Transformer;

use App\Blog\Article\Application\Dto\ArticleMainImageDto;
use App\Image\Domain\Entity\Image;

final class ArticleMainImageTransformer
{
    public function fromImage(Image $image): ArticleMainImageDto
    {
        return new ArticleMainImageDto(
            id: $image->getId()->getValue(),
            name: $image->getName()->getValue()
        );
    }
}
