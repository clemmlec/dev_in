<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $style = null;

    #[ORM\OneToMany(mappedBy: 'categorie_id', targetEntity: Meme::class)]
    private Collection $memes;

    public function __construct()
    {
        $this->memes = new ArrayCollection();
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

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return Collection<int, Meme>
     */
    public function getMemes(): Collection
    {
        return $this->memes;
    }

    public function addMeme(Meme $meme): self
    {
        if (!$this->memes->contains($meme)) {
            $this->memes->add($meme);
            $meme->setCategorieId($this);
        }

        return $this;
    }

    public function removeMeme(Meme $meme): self
    {
        if ($this->memes->removeElement($meme)) {
            // set the owning side to null (unless already changed)
            if ($meme->getCategorieId() === $this) {
                $meme->setCategorieId(null);
            }
        }

        return $this;
    }
}
