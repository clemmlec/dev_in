<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(
    fields: ['email'],
    message: 'cet email est déjà utilisé'
)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre nom ne dois pas dépasser {{ limit }} caracteres'
    )]
    #[Assert\NotBlank]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre email ne dois pas dépasser {{ limit }} caracteres'
    )]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
        message: 'Veuillez rentrer un email valide.'
    )]
    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $style = null;

    #[ORM\Column]
    private ?bool $active = true;

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
    #[Assert\Length(
        max: 255,
        maxMessage: 'Votre mots de passe ne dois pas dépasser {{ limit }} caracteres'
    )]
    #[Assert\Regex(
        pattern: '/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{8,})\S$/',
        message: 'Votre mot de passe doit comporter au moins 8 caractères, une lettre majuscule, une lettre miniscule et 1 chiffre sans espace blanc'
    )]
    private ?string $password = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $rgpd = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Follow::class, orphanRemoval: true)]
    private Collection $follows;

    #[ORM\OneToMany(mappedBy: 'friend', targetEntity: Follow::class, orphanRemoval: true)]
    private Collection $followers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Subject::class)]
    private Collection $subjects;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SubjectFavoris::class, orphanRemoval: true)]
    private Collection $subjectFavoris;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: NoteSubject::class, orphanRemoval: true)]
    private Collection $noteSubjects;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentLike::class, orphanRemoval: true)]
    private Collection $commentLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentReport::class, orphanRemoval: true)]
    private Collection $commentReports;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SubjectReport::class, orphanRemoval: true)]
    private Collection $subjectReports;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ArticleLiked::class, orphanRemoval: true)]
    private Collection $articleLikeds;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ArticleSuggestion::class, orphanRemoval: true)]
    private Collection $articleSuggestions;

    #[ORM\Column(nullable: false, options: ['default' => 0])]
    private ?int $credibility = null;

    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->subjectFavoris = new ArrayCollection();
        $this->noteSubjects = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->commentLikes = new ArrayCollection();
        $this->commentReports = new ArrayCollection();
        $this->subjectReports = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->articleLikeds = new ArrayCollection();
        $this->articleSuggestions = new ArrayCollection();
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
            'style' => $this->style,
            'active' => $this->active,
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
        $this->active = $data['active'];
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
     * @return Collection<int, Subject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->setUser($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getUser() === $this) {
                $subject->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubjectFavoris>
     */
    public function getSubjectFavoris(): Collection
    {
        return $this->subjectFavoris;
    }

    public function addSubjectFavori(SubjectFavoris $subjectFavori): self
    {
        if (!$this->subjectFavoris->contains($subjectFavori)) {
            $this->subjectFavoris->add($subjectFavori);
            $subjectFavori->setUser($this);
        }

        return $this;
    }

    public function removeSubjectFavori(SubjectFavoris $subjectFavori): self
    {
        if ($this->subjectFavoris->removeElement($subjectFavori)) {
            // set the owning side to null (unless already changed)
            if ($subjectFavori->getUser() === $this) {
                $subjectFavori->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteSubject>
     */
    public function getNoteSubjects(): Collection
    {
        return $this->noteSubjects;
    }

    public function addNoteSubject(NoteSubject $noteSubject): self
    {
        if (!$this->noteSubjects->contains($noteSubject)) {
            $this->noteSubjects->add($noteSubject);
            $noteSubject->setUser($this);
        }

        return $this;
    }

    public function removeNoteSubject(NoteSubject $noteSubject): self
    {
        if ($this->noteSubjects->removeElement($noteSubject)) {
            // set the owning side to null (unless already changed)
            if ($noteSubject->getUser() === $this) {
                $noteSubject->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments->filter(function (Comment $comment) {
            return $comment->isActive();
        });
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
     * @return Collection<int, CommentReport>
     */
    public function getCommentReports(): Collection
    {
        return $this->commentReports;
    }

    public function addCommentReport(CommentReport $commentReport): self
    {
        if (!$this->commentReports->contains($commentReport)) {
            $this->commentReports->add($commentReport);
            $commentReport->setUser($this);
        }

        return $this;
    }

    public function removeCommentReport(CommentReport $commentReport): self
    {
        if ($this->commentReports->removeElement($commentReport)) {
            // set the owning side to null (unless already changed)
            if ($commentReport->getUser() === $this) {
                $commentReport->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubjectReport>
     */
    public function getSubjectReports(): Collection
    {
        return $this->subjectReports;
    }

    public function addSubjectReport(SubjectReport $subjectReport): self
    {
        if (!$this->subjectReports->contains($subjectReport)) {
            $this->subjectReports->add($subjectReport);
            $subjectReport->setUser($this);
        }

        return $this;
    }

    public function removeSubjectReport(SubjectReport $subjectReport): self
    {
        if ($this->subjectReports->removeElement($subjectReport)) {
            // set the owning side to null (unless already changed)
            if ($subjectReport->getUser() === $this) {
                $subjectReport->setUser(null);
            }
        }

        return $this;
    }

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
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

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
            $articleLiked->setUser($this);
        }

        return $this;
    }

    public function removeArticleLiked(ArticleLiked $articleLiked): self
    {
        if ($this->articleLikeds->removeElement($articleLiked)) {
            // set the owning side to null (unless already changed)
            if ($articleLiked->getUser() === $this) {
                $articleLiked->setUser(null);
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
            $articleSuggestion->setUser($this);
        }

        return $this;
    }

    public function removeArticleSuggestion(ArticleSuggestion $articleSuggestion): self
    {
        if ($this->articleSuggestions->removeElement($articleSuggestion)) {
            // set the owning side to null (unless already changed)
            if ($articleSuggestion->getUser() === $this) {
                $articleSuggestion->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getCredibility(): ?int
    {
        return $this->credibility;
    }

    public function setCredibility(int $credibility): self
    {
        $this->credibility = $credibility;

        return $this;
    }
}
