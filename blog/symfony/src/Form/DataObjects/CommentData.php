<?php

namespace App\Form\DataObjects;

use Symfony\Component\Validator\Constraints as Assert;

final class CommentData
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(
     *     min=5,
     *     max=300
     * )
     */
    private $text;

    /**
     * @Assert\NotBlank()
     */
    private $parentCommentId;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getParentCommentId()
    {
        return $this->parentCommentId;
    }

    /**
     * @param mixed $parentCommentId
     */
    public function setParentCommentId($parentCommentId): void
    {
        $this->parentCommentId = $parentCommentId;
    }
}
