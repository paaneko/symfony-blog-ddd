<?php

declare(strict_types=1);

namespace App\Image\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'images')]
class Image
{
    #[ORM\Id]
    #[ORM\Column(type: 'image_id', length: 255)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function __construct(Id $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->getId() . '/' . $this->getName();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
