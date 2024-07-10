<?php

namespace App\Auth\User\Application\Test\Builder;

use App\Auth\User\Application\Event\ArticleUserVerifiedEvent;
use App\Blog\Article\Application\Event\ArticleAddRequestEvent;

class ArticleUserVerifiedEventBuilder
{
    public static function createFromArticleAddRequestEvent(ArticleAddRequestEvent $event): ArticleUserVerifiedEvent
    {
        return new ArticleUserVerifiedEvent(
            $event->getTitle(),
            $event->getContent(),
            $event->getCategoryId(),
            $event->getSectionId(),
            $event->getAuthorId(),
            $event->getImageId()
        );
    }
}