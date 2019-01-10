<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $updated_at;

    /* Getters / Setters */

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    private function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    private function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /* Lifecycle hooks */

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt($this->getNowUTC());
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->setUpdatedAt($this->getNowUTC());
    }

    /* Other */

    private function getNowUTC(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
    }
}
