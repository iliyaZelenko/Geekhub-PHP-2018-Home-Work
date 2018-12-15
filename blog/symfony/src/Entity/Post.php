<?php

namespace App\Entity;

class Post
{
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $title;

    /**
     * @Assert\NotBlank
     */
    private $text_short;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $text;

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

    public function getTextShort(): ?string
    {
        return $this->text_short;
    }

    public function setTextShort(string $text_short): self
    {
        $this->text_short = $text_short;

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
}
