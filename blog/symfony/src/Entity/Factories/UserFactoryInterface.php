<?php

namespace App\Entity\Factories;

use App\Entity\UserInterface;
use App\Form\DataObjects\User\UserCreationData;

/**
 * Factory method pattern
 */
interface UserFactoryInterface
{
    public function createNew(UserCreationData $data): UserInterface;
}
