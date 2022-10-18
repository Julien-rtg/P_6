<?php

namespace App\Entity;

use App\Repository\VideoFigureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoFigureRepository::class)]
class VideoFigure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?figure $id_figure = null;

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

    public function getIdFigure(): ?figure
    {
        return $this->id_figure;
    }

    public function setIdFigure(?figure $id_figure): self
    {
        $this->id_figure = $id_figure;

        return $this;
    }

}
