<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'article')]
    private Collection $tags;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleLiked::class, orphanRemoval: true)]
    private Collection $articleLikeds;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleSuggestion::class, orphanRemoval: true)]
    private Collection $articleSuggestions;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->articleLikeds = new ArrayCollection();
        $this->articleSuggestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ArticleLiked>
     */
    public function getArticleLikeds(): Collection
    {
        return $this->articleLikeds;
    }

    public function addArticleLiked(ArticleLiked $articleLiked): self
    {
        if (!$this->articleLikeds->contains($articleLiked)) {
            $this->articleLikeds->add($articleLiked);
            $articleLiked->setArticle($this);
        }

        return $this;
    }

    public function removeArticleLiked(ArticleLiked $articleLiked): self
    {
        if ($this->articleLikeds->removeElement($articleLiked)) {
            // set the owning side to null (unless already changed)
            if ($articleLiked->getArticle() === $this) {
                $articleLiked->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ArticleSuggestion>
     */
    public function getArticleSuggestions(): Collection
    {
        return $this->articleSuggestions;
    }

    public function addArticleSuggestion(ArticleSuggestion $articleSuggestion): self
    {
        if (!$this->articleSuggestions->contains($articleSuggestion)) {
            $this->articleSuggestions->add($articleSuggestion);
            $articleSuggestion->setArticle($this);
        }

        return $this;
    }

    public function removeArticleSuggestion(ArticleSuggestion $articleSuggestion): self
    {
        if ($this->articleSuggestions->removeElement($articleSuggestion)) {
            // set the owning side to null (unless already changed)
            if ($articleSuggestion->getArticle() === $this) {
                $articleSuggestion->setArticle(null);
            }
        }

        return $this;
    }

        public function __toString()
    {
        return $this->name;
    }
}
