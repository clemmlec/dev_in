<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $style = null;

    #[ORM\Column]
    private ?bool $visible = true;

    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $image_size = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $image_updated_at = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $rgpd = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Follow::class, orphanRemoval: true)]
    private Collection $follows;

    #[ORM\OneToMany(mappedBy: 'friend', targetEntity: Follow::class, orphanRemoval: true)]
    private Collection $followers;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ArticleFavoris::class, orphanRemoval: true)]
    private Collection $articleFavoris;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: NoteArticle::class, orphanRemoval: true)]
    private Collection $noteArticles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentLike::class, orphanRemoval: true)]
    private Collection $commentLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentSignaler::class, orphanRemoval: true)]
    private Collection $commentSignalers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ArticleSignaler::class, orphanRemoval: true)]
    private Collection $articleSignalers;

    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->articleFavoris = new ArrayCollection();
        $this->noteArticles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->commentLikes = new ArrayCollection();
        $this->commentSignalers = new ArrayCollection();
        $this->articleSignalers = new ArrayCollection();
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
            'style' => $this->style,
            'visible' => $this->visible,
            'image_name' => $this->image_name,
            'updated_at' => $this->updated_at,
            'password' => $this->password,
            'rgpd' => $this->rgpd,
        ];
    }

    public function __unserialize(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->roles = $data['roles'];
        $this->style = $data['style'];
        $this->visible = $data['visible'];
        $this->image_name = $data['image_name'];
        $this->updated_at = $data['updated_at'];
        $this->password = $data['password'];
        $this->rgpd = $data['rgpd'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->image_updated_at = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(?string $image_name): self
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->image_size;
    }

    public function setImageSize(?int $image_size): self
    {
        $this->image_size = $image_size;

        return $this;
    }

    public function getImageUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->image_updated_at;
    }

    public function setImageUpdatedAt(?\DateTimeImmutable $image_updated_at): self
    {
        $this->image_updated_at = $image_updated_at;

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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(Follow $follow): self
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
            $follow->setUser($this);
        }

        return $this;
    }

    public function removeFollow(Follow $follow): self
    {
        if ($this->follows->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getUser() === $this) {
                $follow->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    // public function addFollower(Follow $follower): self
    // {
    //     if (!$this->followers->contains($follower)) {
    //         $this->followers->add($follower);
    //         $follower->setFriend($this);
    //     }

    //     return $this;
    // }

    // public function removeFollower(Follow $follower): self
    // {
    //     if ($this->followers->removeElement($follower)) {
    //         // set the owning side to null (unless already changed)
    //         if ($follower->getFriend() === $this) {
    //             $follower->setFriend(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUserId($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUserId() === $this) {
                $article->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ArticleFavoris>
     */
    public function getArticleFavoris(): Collection
    {
        return $this->articleFavoris;
    }

    public function addArticleFavori(ArticleFavoris $articleFavori): self
    {
        if (!$this->articleFavoris->contains($articleFavori)) {
            $this->articleFavoris->add($articleFavori);
            $articleFavori->setUser($this);
        }

        return $this;
    }

    public function removeArticleFavori(ArticleFavoris $articleFavori): self
    {
        if ($this->articleFavoris->removeElement($articleFavori)) {
            // set the owning side to null (unless already changed)
            if ($articleFavori->getUser() === $this) {
                $articleFavori->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteArticle>
     */
    public function getNoteArticles(): Collection
    {
        return $this->noteArticles;
    }

    public function addNoteArticle(NoteArticle $noteArticle): self
    {
        if (!$this->noteArticles->contains($noteArticle)) {
            $this->noteArticles->add($noteArticle);
            $noteArticle->setUser($this);
        }

        return $this;
    }

    public function removeNoteArticle(NoteArticle $noteArticle): self
    {
        if ($this->noteArticles->removeElement($noteArticle)) {
            // set the owning side to null (unless already changed)
            if ($noteArticle->getUser() === $this) {
                $noteArticle->setUser(null);
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentLike>
     */
    public function getCommentLikes(): Collection
    {
        return $this->commentLikes;
    }

    public function addCommentLike(CommentLike $commentLike): self
    {
        if (!$this->commentLikes->contains($commentLike)) {
            $this->commentLikes->add($commentLike);
            $commentLike->setUser($this);
        }

        return $this;
    }

    public function removeCommentLike(CommentLike $commentLike): self
    {
        if ($this->commentLikes->removeElement($commentLike)) {
            // set the owning side to null (unless already changed)
            if ($commentLike->getUser() === $this) {
                $commentLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentSignaler>
     */
    public function getCommentSignalers(): Collection
    {
        return $this->commentSignalers;
    }

    public function addCommentSignaler(CommentSignaler $commentSignaler): self
    {
        if (!$this->commentSignalers->contains($commentSignaler)) {
            $this->commentSignalers->add($commentSignaler);
            $commentSignaler->setUser($this);
        }

        return $this;
    }

    public function removeCommentSignaler(CommentSignaler $commentSignaler): self
    {
        if ($this->commentSignalers->removeElement($commentSignaler)) {
            // set the owning side to null (unless already changed)
            if ($commentSignaler->getUser() === $this) {
                $commentSignaler->setUser(null);
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
            $articleSignaler->setUser($this);
        }

        return $this;
    }

    public function removeArticleSignaler(ArticleSignaler $articleSignaler): self
    {
        if ($this->articleSignalers->removeElement($articleSignaler)) {
            // set the owning side to null (unless already changed)
            if ($articleSignaler->getUser() === $this) {
                $articleSignaler->setUser(null);
            }
        }

        return $this;
    }
}
