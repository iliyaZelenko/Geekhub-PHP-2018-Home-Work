<?php

namespace App\Repository;

use App\Entity\UserInterface;

interface UserRepositoryInterface
{
    /**
     * Get first entity
     *
     * @return UserInterface|null
     */
    public function getFirst(): ?UserInterface;

    /**
     * Save entity
     *
     * @param UserInterface $user
     */
    public function save(UserInterface $user): void;

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed    $id          The identifier.
     * @param int|null $lockMode    One of the \Doctrine\DBAL\LockMode::* constants
     *                              or NULL if no specific lock mode should be used
     *                              during the search.
     * @param int|null $lockVersion The lock version.
     *
     * @return UserInterface|null The entity instance or NULL if the entity can not be found.
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return UserInterface|null
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll();

    /**
     * Finds entities by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}
