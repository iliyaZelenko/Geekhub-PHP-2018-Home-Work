<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE_PREFIX = 'user';
    public const COUNT = 3;
    public const USERS = [
        [
            'username' => 'Василько-с_района.КРУТОЙ',
            'email' => 'vasilko@example.com'
        ],
        [
            'username' => 'Неуравновешанный_хомяк_убийца',
            'email' => 'killer@example.com'
        ],
        [
            'username' => 'ПьянаяяМартышка',
            'email' => 'obezyana@example.com'
        ]
    ];
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::COUNT; ++$i) {
            [
               'username' => $username,
               'email' => $email
            ] = static::USERS[$i - 1];
            // random string
            $plainPassword = 'just text ' . bin2hex(random_bytes(10));
            $user = new User($username, $email);

            $user->setPassword(
                $password = $this->passwordEncoder->encodePassword($user, $plainPassword)
            );
            $this->addReference(self::REFERENCE_PREFIX . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 99;
    }
}
