<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields = {"email"},
 * message = "Cet email est déja utilisé")
 * @UniqueEntity(
 * fields = {"username"},
 * message = "Ce nom d'utilisateur est déja utilisé")
 */
class User implements UserInterface
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Mots de passe différents")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAuth;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumComments", mappedBy="author")
     */
    private $forumComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumTopics", mappedBy="author")
     */
    private $forumTopics;

    public function __construct()
    {
        $this->forumComments = new ArrayCollection();
        $this->forumTopics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    // Méthodes de l'interface UserInterface

    public function eraseCredentials(){}

    public function getSalt(){}

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getIsAuth(): ?bool
    {
        return $this->isAuth;
    }

    public function setIsAuth(bool $isAuth): self
    {
        $this->isAuth = $isAuth;

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
            $forumComment->setAuthor($this);
        }

        return $this;
    }

    public function removeForumComment(ForumComments $forumComment): self
    {
        if ($this->forumComments->contains($forumComment)) {
            $this->forumComments->removeElement($forumComment);
            // set the owning side to null (unless already changed)
            if ($forumComment->getAuthor() === $this) {
                $forumComment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumTopics[]
     */
    public function getForumTopics(): Collection
    {
        return $this->forumTopics;
    }

    public function addForumTopic(ForumTopics $forumTopic): self
    {
        if (!$this->forumTopics->contains($forumTopic)) {
            $this->forumTopics[] = $forumTopic;
            $forumTopic->setAuthor($this);
        }

        return $this;
    }

    public function removeForumTopic(ForumTopics $forumTopic): self
    {
        if ($this->forumTopics->contains($forumTopic)) {
            $this->forumTopics->removeElement($forumTopic);
            // set the owning side to null (unless already changed)
            if ($forumTopic->getAuthor() === $this) {
                $forumTopic->setAuthor(null);
            }
        }

        return $this;
    }

}
