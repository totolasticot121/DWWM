<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumTopicsRepository")
 */
class ForumTopics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumComments", mappedBy="forumTopic", orphanRemoval=true)
     */
    private $forumComments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategory", inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forumCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumTopics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


    public function __construct()
    {
        $this->forumComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|ForumComments[]
     */
    public function getForumComments(): Collection
    {
        return $this->forumComments;
    }

    public function addForumComment(ForumComments $forumComment): self
    {
        if (!$this->forumComments->contains($forumComment)) {
            $this->forumComments[] = $forumComment;
            $forumComment->setForumTopic($this);
        }

        return $this;
    }

    public function removeForumComment(ForumComments $forumComment): self
    {
        if ($this->forumComments->contains($forumComment)) {
            $this->forumComments->removeElement($forumComment);
            // set the owning side to null (unless already changed)
            if ($forumComment->getForumTopic() === $this) {
                $forumComment->setForumTopic(null);
            }
        }

        return $this;
    }

    public function getForumCategory(): ?ForumCategory
    {
        return $this->forumCategory;
    }

    public function setForumCategory(?ForumCategory $forumCategory): self
    {
        $this->forumCategory = $forumCategory;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
