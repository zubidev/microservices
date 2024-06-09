<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UsersRepository
 *
 * This class serves as the repository for the Users entity,
 * providing specific methods for querying or persisting user data.
 *
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository
{
    /**
     * UsersRepository constructor.
     *
     * @param ManagerRegistry $registry The registry of entity managers
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    // Add custom query methods here if needed
}
