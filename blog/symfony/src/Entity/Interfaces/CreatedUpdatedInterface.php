<?php

namespace App\Entity\Interfaces;

interface CreatedUpdatedInterface
{
    public function setCreatedAt(\DateTimeImmutable $createdAt);
    public function setUpdatedAt(\DateTimeImmutable $updatedAt);
}
