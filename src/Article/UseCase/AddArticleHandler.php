<?php

declare(strict_types=1);

namespace App\Article\UseCase;

use App\Article\ArticleService;
use App\Article\Entity\Article;
use App\Article\Entity\Id as ArticleId;
use App\Image\Entity\Id as ImageId;
use App\Image\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

class AddArticleHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ArticleService $articleService,
    ) {
    }

    public function handle(AddArticleCommand $addArticleCommand): Article
    {
        $article = new Article(
            ArticleId::generate(),
            $addArticleCommand->title,
            $addArticleCommand->content,
            new Image(
                new ImageId($addArticleCommand->imageId),
                $addArticleCommand->imageName
            ),
            new \DateTimeImmutable()
        );

        try {
            $this->entityManager->beginTransaction();
            $this->articleService->add($article);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $article;
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            // TODO add log
            throw $exception;
        }
    }
}
