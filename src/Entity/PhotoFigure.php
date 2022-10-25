<?php

namespace App\Entity;

use App\Repository\PhotoFigureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoFigureRepository::class)]
class PhotoFigure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'photoFigures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Figure $id_figure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getIdFigure(): ?Figure
    {
        return $this->id_figure;
    }

    public function setIdFigure(?Figure $id_figure): self
    {
        $this->id_figure = $id_figure;

        return $this;
    }

}
