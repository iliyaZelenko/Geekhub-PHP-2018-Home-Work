<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function paginated()
    {
        // TODO
    }

    public function getWithRootComments($id)
    {
        return $this->createQueryBuilder('post')
            ->innerJoin('post.comments', 'post_comments')
//            ->innerJoin('post_comments.childrenComments', 'post_comments_comments')
            ->andWhere('post.id = :id')
            ->andWhere('post_comments.parent_id is NULL')
            ->setParameters([
                'id' => $id
            ])
            ->addSelect('post_comments')
            ->orderBy('post_comments.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
//            ->getResult()
        ;
    }

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
