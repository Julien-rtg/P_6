<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigureRepository::class)]
class Figure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public ?string $stringGroupeFigure;
    public ?string $slug;

    #[ORM\Column]
    private ?int $groupe_figure = null;

    #[ORM\OneToMany(mappedBy: 'id_figure', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'figures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $id_utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'id_figure', targetEntity: VideoFigure::class, cascade:['persist'])]
    private Collection $videoFigures;

    #[ORM\OneToMany(mappedBy: 'id_figure', targetEntity: PhotoFigure::class, cascade:['persist'])]
    private Collection $photoFigures;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modification = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->videoFigures = new ArrayCollection();
        $this->photoFigures = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGroupeFigure(): ?int
    {
        return $this->groupe_figure;
    }

    public function setGroupeFigure(int $groupe_figure): self
    {
        $this->groupe_figure = $groupe_figure;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdFigure($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdFigure() === $this) {
                $commentaire->setIdFigure(null);
            }
        }

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, VideoFigure>
     */
    public function getVideoFigures(): Collection
    {
        return $this->videoFigures;
    }

    public function addVideoFigure(VideoFigure $videoFigure): self
    {
        if (!$this->videoFigures->contains($videoFigure)) {
            $this->videoFigures->add($videoFigure);
            $videoFigure->setIdFigure($this);
        }

        return $this;
    }

    public function removeVideoFigure(VideoFigure $videoFigure): self
    {
        if ($this->videoFigures->removeElement($videoFigure)) {
            // set the owning side to null (unless already changed)
            if ($videoFigure->getIdFigure() === $this) {
                $videoFigure->setIdFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PhotoFigure>
     */
    public function getPhotoFigures(): Collection
    {
        return $this->photoFigures;
    }

    public function addPhotoFigure(PhotoFigure $photoFigure): self
    {
        if (!$this->photoFigures->contains($photoFigure)) {
            $this->photoFigures->add($photoFigure);
            $photoFigure->setIdFigure($this);
        }

        return $this;
    }

    public function removePhotoFigure(PhotoFigure $photoFigure): self
    {
        if ($this->photoFigures->removeElement($photoFigure)) {
            // set the owning side to null (unless already changed)
            if ($photoFigure->getIdFigure() === $this) {
                $photoFigure->setIdFigure(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

}
