<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Utils\Slugger\Slugger;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints\Collection;

// use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// @UniqueEntity("slug")
/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
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
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $textShort;

    // было unique=true, но пост ищется по id, поэтому конфликта не будет, ничего страшного думаю не будет
    // если одинаковый слуг, он же относится к контенту, не нужно будет делать проверку уникальнсоти слугов и для
    // сохранения уникальности добавлять число на конец слуга, которое только будет мешать seo(не относится к заголовку)
    // Например, допустим посты начали писать разные пользователи, один сделал пост со слугом "kak-rabotat-s-symfony-4"
    // (Как работать с Symfony 4), потом второй сделал такой же заголовок, но слуг уже будет "kak-rabotat-s-symfony-42"
    // (добавилось 2 для уникальности слуга), получилось что версия не 4, а 42, как по мне, это меняет смысл поста,
    // из-за чего плохо для сео.
    /**
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /* Relations */

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
//    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="posts_tags",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

//, inversedBy="posts"
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $author;

    /**
     * Post constructor.
     * @param User $author
     * @param string $title
     * @param string $text
     * @param string $textShort
     * @param Tag[] $tags
     */
    public function __construct(User $author, string $title, string $text, string $textShort, $tags = [])
    {
//        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

//        $author->addPost($this);
        $this
            ->setAuthor($author)
            ->setTitle($title)
            ->setText($text)
            ->setTextShort($textShort);
    }

    /* Getters / Setters */

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setSlug(
            Slugger::slugify($title)
        );

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTextShort(): string
    {
        return $this->textShort;
    }

    public function setTextShort(string $textShort): self
    {
        $this->textShort = $textShort;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
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
//            $comment->setPost($this);
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
//            if ($comment->getPost() === $this) {
//                $comment->setPost(null);
//            }
//        }
//
//        return $this;
//    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
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

    private function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
