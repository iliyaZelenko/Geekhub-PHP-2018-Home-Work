<?php

namespace App\Entity\Factories;

use App\Entity\UserInterface;
use App\Form\DataObjects\RegistrationData;

/**
 * Factory method pattern
 */
interface UserFactoryInterface
{
    public function createNew(RegistrationData $data): UserInterface;
}
