<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre titre ne dois pas dépasser {{ limit }} caracteres'
    )]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Forum $forum = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: NoteSubject::class, orphanRemoval: true)]
    private Collection $noteSubjects;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: SubjectReport::class)]
    private Collection $subjectReports;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: SubjectFavoris::class)]
    private Collection $subjectFavoris;

    public function __construct()
    {
        $this->noteSubjects = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->subjectReports = new ArrayCollection();
        $this->subjectFavoris = new ArrayCollection();
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->nom,
            $this->description,
            $this->user,
            $this->forum,
            $this->created_at,
            $this->updated_at,
            $this->active,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->nom,
            $this->description,
            $this->user,
            $this->forum,
            $this->created_at,
            $this->updated_at,
            $this->active
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getForum(): ?Forum
    {
        return $this->forum;
    }

    public function setForum(?Forum $forum): self
    {
        $this->forum = $forum;

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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, NoteSubject>
     */
    public function getNoteSubjects(): Collection
    {
        return $this->noteSubjects;
    }
    // public function getMoyenneSubjects(): array
    // {
    //     return $this->noteSubjects;
    // }

    public function addNoteSubject(NoteSubject $noteSubject): self
    {
        if (!$this->noteSubjects->contains($noteSubject)) {
            $this->noteSubjects->add($noteSubject);
            $noteSubject->setSubject($this);
        }

        return $this;
    }

    public function removeNoteSubject(NoteSubject $noteSubject): self
    {
        if ($this->noteSubjects->removeElement($noteSubject)) {
            // set the owning side to null (unless already changed)
            if ($noteSubject->getSubject() === $this) {
                $noteSubject->setSubject(null);
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
            $comment->setSubject($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getSubject() === $this) {
                $comment->setSubject(null);
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
            $subjectReport->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectReport(SubjectReport $subjectReport): self
    {
        if ($this->subjectReports->removeElement($subjectReport)) {
            // set the owning side to null (unless already changed)
            if ($subjectReport->getSubject() === $this) {
                $subjectReport->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubjectFavoris>
     */
    public function getSubjectFavoriss(): Collection
    {
        return $this->subjectFavoris;
    }

    public function addSubjectFavoris(SubjectFavoris $subjectFavoris): self
    {
        if (!$this->subjectFavoris->contains($subjectFavoris)) {
            $this->subjectFavoris->add($subjectFavoris);
            $subjectFavoris->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectFavoris(SubjectFavoris $subjectFavoris): self
    {
        if ($this->subjectFavoris->removeElement($subjectFavoris)) {
            // set the owning side to null (unless already changed)
            if ($subjectFavoris->getSubject() === $this) {
                $subjectFavoris->setSubject(null);
            }
        }

        return $this;
    }
}
