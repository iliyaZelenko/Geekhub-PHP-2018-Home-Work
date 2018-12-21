<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;

//TODO не уверен что я правильно сделал @UniqueEntity, мне нужна уникальность по отдельности, а не в связке,
//в миграции вроде создало только уникальнсоть для email
/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity({"username", "email"})
 */
class User implements UserInterface, \Serializable
{
    /* Columns */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * Допускаются русские символы (почему никнейм обязательно должен быть на английском?).
     * @Assert\Regex("/^[а-яА-Яa-zA-ZЁё][а-яА-Яa-zA-Z0-9Ёё]*?([-_.][а-яА-Яa-zA-Z0-9Ёё]+){0,3}$/u")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    // TODO походу важно указывать name="..." если в другом регистре
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /* Relations */

    // TODO Подумать над односторонными связями. Походу эту свзяь убрать.
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
//    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
//    private $comments;

    public function __construct(string $username, string $email, string $password)
    {
//        $this->comments = new ArrayCollection();
//        $this->posts = new ArrayCollection();

        $this->isActive = true;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /* Getters / Setters */

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /* Relations */

//    public function getComments(): Collection
//    {
//        return $this->comments;
//    }
//
//    public function addComment(Comment $comment): self
//    {
//        if (!$this->comments->contains($comment)) {
//            $this->comments[] = $comment;
//            $comment->setAuthor($this);
//        }
//
//        return $this;
//    }
//
//    public function removeComment(Comment $comment): self
//    {
//        if ($this->comments->contains($comment)) {
//            $this->comments->removeElement($comment);
//            // set the owning side to null (unless already changed)
//            if ($comment->getAuthor() === $this) {
//                $comment->setAuthor(null);
//            }
//        }
//
//        return $this;
//    }
//
//    public function getPosts(): Collection
//    {
//        return $this->posts;
//    }
//
//    public function addPost(Post $post): self
//    {
//        if (!$this->posts->contains($post)) {
//            $this->posts[] = $post;
//            $post->setAuthor($this);
//        }
//
//        return $this;
//    }
//
//    public function removePost(Post $post): self
//    {
//        if ($this->posts->contains($post)) {
//            $this->posts->removeElement($post);
//            // set the owning side to null (unless already changed)
//            if ($post->getAuthor() === $this) {
//                $post->setAuthor(null);
//            }
//        }
//
//        return $this;
//    }

    /* Other */

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized): void
    {
        // TODO был list, проверить работает ли
        [
            $this->id,
            $this->username,
            $this->password,
        ] = unserialize($serialized, [
            'allowed_classes' => false
        ]);
    }
}
