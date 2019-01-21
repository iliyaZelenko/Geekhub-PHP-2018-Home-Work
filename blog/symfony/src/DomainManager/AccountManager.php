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
    // TODO DI
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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

        // Внедрил через интерфейс вместо этого: $entityManager = $this->getDoctrine()->getManager();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}


//class AccountManager
//{
//    private $entityManager;
//    private $secureRandom;
//
//    public function __construct(
//        EntityManger $entityManager,
//        SecureRandomInterface $secureRandom
//    ) {
//        $this->entityManager = $entityManager;
//        $this->secureRandom = $secureRandom;
//    }
//
//    public function createAccount(Account $account)
//    {
//        $confirmationCode = $this
//            ->secureRandom
//            ->nextBytes(4);
//
//        $account
//            ->setConfirmationCode(md5($confirmationCode));
//
//        $this->entityManager->persist($account);
//        $this->entityManager->flush();
//    }
//}
