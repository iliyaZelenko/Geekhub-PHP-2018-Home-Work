<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface as PaginationInterfaceReturn;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);

        $this->paginator = $paginator;
    }

    public function getPaginated($page, $perPage): PaginationInterfaceReturn
    {
        $query = $this
            ->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
        ;

        // Возвращается экземпляр: https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/Pagination/SlidingPagination.php
        return $this->paginator->paginate(
            $query,
            $page,
            $perPage
        );
    }
}
