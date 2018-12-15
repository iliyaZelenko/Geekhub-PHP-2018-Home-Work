<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent_id;

    /**
     * TODO если используется отношение то вроде это не нужно
     * @ORM\Column(type="integer")
     */
    private $author_id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      max = 100
     * )
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="parent", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $childrenComments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="childrenComments")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /* Getters / Setters */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /* Relations */

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getParent(): Comment
    {
        return $this->parent;
    }

    public function setParent(Comment $comment): self
    {
        $this->parent = $comment;

        return $this;
    }

    public function getChildrenComments()
    {
        return $this->childrenComments;
    }

    public function addChildrenComment(Comment $comment): self
    {
        if (!$this->childrenComments->contains($comment)) {
            $this->childrenComments[] = $comment;
            $comment->getParent($this);
        }

        return $this;
    }
}
