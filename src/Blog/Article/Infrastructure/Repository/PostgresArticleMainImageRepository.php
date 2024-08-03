<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Repository;

use App\Blog\Article\Application\Dto\ArticleMainImageDto;
use App\Blog\Article\Application\Repository\ArticleMainImageRepositoryInterface;
use App\Blog\Article\Application\Transformer\ArticleMainImageTransformer;
use App\Blog\Article\Domain\Exception\ArticleMainImageNotFoundException;
use App\Image\Application\Service\ImageService;
use App\Image\Domain\ValueObject\ImageId;

/** Provide Repository Collection from external BC's */
final class PostgresArticleMainImageRepository implements ArticleMainImageRepositoryInterface
{
    public function __construct(
        private ImageService $imageService,
        private ArticleMainImageTransformer $articleMainImageTransformer
    ) {
    }

    public function getById(string $id): ArticleMainImageDto
    {
        $image = $this->imageService->find(new ImageId($id));

        if (null === $image) {
            throw new ArticleMainImageNotFoundException();
        }

        return $this->articleMainImageTransformer->fromImage($image);
    }
}
