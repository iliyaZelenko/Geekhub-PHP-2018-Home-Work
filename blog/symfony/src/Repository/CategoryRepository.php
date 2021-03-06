<?php

namespace App\Repository;

use App\Entity\Post;
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

//use Gedmo\Tree\Traits\Repository\ORM\NestedTreeRepositoryTrait;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{
//    use NestedTreeRepositoryTrait; // or MaterializedPathRepositoryTrait or ClosureTreeRepositoryTrait.
//    public function __construct(RegistryInterface $registry)
//    {
//        parent::__construct($registry, Post::class);
//    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
