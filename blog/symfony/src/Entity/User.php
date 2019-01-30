<?php

namespace App\Entity;

use App\Entity\Resources\CreatedUpdatedInterface;
use App\Entity\Resources\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;

//TODO не уверен что я правильно сделал @UniqueEntity, мне нужна уникальность по отдельности, а не в связке,
//в миграции вроде создало только уникальнсоть для email
/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity({"username", "email"})
 */
class User implements UserInterface, \Serializable, CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    /* Columns */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
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
     */
    private $email;

    /* Relations */

    // TODO Подумать над односторонными связями. Походу эту свзяь убрать.
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author", orphanRemoval=true)
//     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
//     * @param string $username
//     * @param string $email
//     * @param string $password
//     */
//    private $posts;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
//     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
//     */
//    private $comments;

    public function __construct(string $username, string $email)
    {
//        $this->comments = new ArrayCollection();
//        $this->posts = new ArrayCollection();

        $this->isActive = true;
        $this->username = $username;
        $this->email = $email;
    }

    /* Getters / Setters */

    public function getId()
    {
        return $this->id;
    }

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
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

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        // например, чтобы не палить пароль (у меня нет plainPassword)
        // $this->plainPassword = null;
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
