<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    use TimestampableTrait;

    /* Columns */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(
     *     min=5,
     *     max=100
     * )
     */
    private $text;

    /* Relations */

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $post;

    // TODO не пойму, можно ли указывать onDelete="CASCADE" с каждой стороны и обязательно ли во всех сторонах указывать.
    // TODO почему тут нельзя сделать OneToOne, а нужно ManyToOne?
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

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

    // TODO сразу тут ставить $post и $author_id
    public function __construct(User $author, Post $post)
    {
        $this->childrenComments = new ArrayCollection();

        $author->addComment($this);
        $post->addComment($this);
    }

    /* Getters / Setters */

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChildrenComments(): Collection
    {
        return $this->childrenComments;
    }

    public function addChildrenComment(Comment $comment): self
    {
        if (!$this->childrenComments->contains($comment)) {
            $this->childrenComments[] = $comment;
            $comment->setParent($this);
        }

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
