<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

// TODO EventListener
trait TimestampableTrait
{
    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $updatedAt;

    /* Getters / Setters */

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /* Lifecycle hooks */

//    /**
//     * @ORM\PrePersist()
//     */
//    public function setCreatedAtValue()
//    {
//        $this->setCreatedAt($this->getNowUTC());
//    }
//
//    /**
//     * @ORM\PreUpdate()
//     */
//    public function onPreUpdate()
//    {
//        $this->setUpdatedAt($this->getNowUTC());
//    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /* Other */

//    private function getNowUTC(): \DateTimeImmutable
//    {
//        return new \DateTimeImmutable(
//            'now',
//            new \DateTimeZone('UTC')
//        );
//    }
}
