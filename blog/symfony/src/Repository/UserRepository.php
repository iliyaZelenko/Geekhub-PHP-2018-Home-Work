<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        // TODO поппробовать указать интерфейс UserInterface
        parent::__construct($registry, User::class);
    }

    public function getFirst(): ?UserInterface
    {
        return $this
            ->createQueryBuilder('comment')
            ->setMaxResults(1)
            ->orderBy('comment.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Save entity
     *
     * @param UserInterface $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserInterface $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
