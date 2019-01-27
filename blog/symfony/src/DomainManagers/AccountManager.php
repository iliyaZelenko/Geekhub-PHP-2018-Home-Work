<?php

namespace App\DomainManagers;

use App\Entity\User;
use App\Form\DataObjects\RegistrationData;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, UserRepository $userRepo)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->userRepo = $userRepo;
    }

    public function createAccount(RegistrationData $data): User
    {
        $username = $data->getUsername();

        if ($this->userRepo->findOneBy([
            'username' => $username
        ])) {
            // TODO по идее не должно быть связанности с Http исключениями, не знаю как сделать
            throw new HttpException(409, 'A user with this nickname already exists.');
        }

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
