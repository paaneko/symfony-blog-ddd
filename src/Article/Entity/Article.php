<?php

declare(strict_types=1);

namespace App\Article\Entity;

use App\Image\Entity\Image;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
class Article
{
    #[ORM\Id]
    #[ORM\Column(type: 'article_id', length: 255)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;
    #[ORM\Column(type: 'text', length: 1000)]
    private string $content;

    #[ORM\OneToOne(targetEntity: Image::class, cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private Image $image;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $dateTime;

    public function __construct(Id $id, string $title, string $content, Image $image, \DateTimeInterface $dateTime)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->dateTime = $dateTime;
    }

    /** @psalm-suppress UnusedMethod */
    public function getId(): Id
    {
        return $this->id;
    }

    /** @psalm-suppress UnusedMethod */
    public function getTitle(): string
    {
        return $this->title;
    }

    /** @psalm-suppress UnusedMethod */
    public function getContent(): string
    {
        return $this->content;
    }

    /** @psalm-suppress UnusedMethod */
    public function getImage(): Image
    {
        return $this->image;
    }

    /** @psalm-suppress UnusedMethod */
    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }
}
