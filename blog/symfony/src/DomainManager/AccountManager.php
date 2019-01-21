<?php

namespace App\DomainManager;

use App\Entity\User;
use App\Form\DataObjects\RegistrationData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class AccountManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    // TODO DI
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    // User $user
    public function createAccount(RegistrationData $data): User
    {
        // до этого было new User('', ''); А теперь Entity всегда валидна как это и должно быть.
        $user = new User(
            $data->getUsername(),
            $data->getEmail()
        );

        // encode the plain password
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $data->getPlainPassword()
            )
        );
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
