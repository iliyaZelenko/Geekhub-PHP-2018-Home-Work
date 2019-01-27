<?php

namespace App\Entity;

use App\Entity\Interfaces\CreatedUpdatedInterface;
use App\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="posts_votes")
 * @ORM\Entity()
 */
class PostVote implements CreatedUpdatedInterface
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
     * 1 or -1. Можно было сделать через bool, для гибкости решил так.
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /* Relations */

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="votes")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    public function __construct(User $user, Post $post, string $value)
    {
        $this
            ->setUser($user)
            ->setPost($post)
            ->setValue($value)
        ;
    }

    /* Getters / Setters */

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return PostVote
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return PostVote
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     * @return PostVote
     */
    public function setPost($post): self
    {
        $this->post = $post;

        return $this;
    }
}
