<?php

namespace App\Entity\Interfaces;

interface SluggableInterface
{
    public function getSlugAttributes(): array;
}
