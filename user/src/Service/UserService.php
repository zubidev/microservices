<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserService
 *
 * This class provides services related to user operations,
 * including creation and retrieval.
 */
class UserService
{
    private EntityManagerInterface $entityManager;
    private UsersRepository $userRepository;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager
     * @param UsersRepository $userRepository The user repository
     */
    public function __construct(EntityManagerInterface $entityManager, UsersRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user.
     *
     * @param string $email The email of the user
     * @param string $firstName The first name of the user
     * @param string $lastName The last name of the user
     * @return int The ID of the created user
     * @throws \Exception If there is an error persisting the user
     */
    public function createUser(string $email, string $firstName, string $lastName): int
    {
        try {
            $user = new Users();
            $user->setEmail($email)
                ->setFirstName($firstName)
                ->setLastName($lastName);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user->getId();
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }
}
