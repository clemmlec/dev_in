<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie_id = null;

    #[Vich\UploadableField(mapping: 'article', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $imageUpdatedAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?bool $visible = true;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: NoteArticle::class, orphanRemoval: true)]
    private Collection $noteArticles;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleSignaler::class)]
    private Collection $articleSignalers;

    public function __construct()
    {
        $this->noteArticles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->articleSignalers = new ArrayCollection();
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->nom,
            $this->description,
            $this->user_id,
            $this->categorie_id,
            $this->imageName,
            $this->created_at,
            $this->updated_at,
            $this->visible,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->nom,
            $this->description,
            $this->user_id,
            $this->categorie_id,
            $this->imageName,
            $this->created_at,
            $this->updated_at,
            $this->visible
        ) = unserialize($serialized);
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCategorieId(): ?Categorie
    {
        return $this->categorie_id;
    }

    public function setCategorieId(?Categorie $categorie_id): self
    {
        $this->categorie_id = $categorie_id;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->imageUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getImageUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->imageUpdatedAt;
    }

    public function setImageUpdatedAt(?\DateTimeImmutable $imageUpdatedAt): self
    {
        $this->imageUpdatedAt = $imageUpdatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection<int, NoteArticle>
     */
    public function getNoteArticles(): Collection
    {
        return $this->noteArticles;
    }
    // public function getMoyenneArticles(): array
    // {
    //     return $this->noteArticles;
    // }

    public function addNoteArticle(NoteArticle $noteArticle): self
    {
        if (!$this->noteArticles->contains($noteArticle)) {
            $this->noteArticles->add($noteArticle);
            $noteArticle->setArticle($this);
        }

        return $this;
    }

    public function removeNoteArticle(NoteArticle $noteArticle): self
    {
        if ($this->noteArticles->removeElement($noteArticle)) {
            // set the owning side to null (unless already changed)
            if ($noteArticle->getArticle() === $this) {
                $noteArticle->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ArticleSignaler>
     */
    public function getArticleSignalers(): Collection
    {
        return $this->articleSignalers;
    }

    public function addArticleSignaler(ArticleSignaler $articleSignaler): self
    {
        if (!$this->articleSignalers->contains($articleSignaler)) {
            $this->articleSignalers->add($articleSignaler);
            $articleSignaler->setArticle($this);
        }

        return $this;
    }

    public function removeArticleSignaler(ArticleSignaler $articleSignaler): self
    {
        if ($this->articleSignalers->removeElement($articleSignaler)) {
            // set the owning side to null (unless already changed)
            if ($articleSignaler->getArticle() === $this) {
                $articleSignaler->setArticle(null);
            }
        }

        return $this;
    }
}
