<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tag
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
    private $name;

    public function __construct(string $name)
    {
        $this
            ->setName($name)
        ;
    }

    /* Getters / Setters */

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}