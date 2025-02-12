<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,

    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Method to get a user by its email
     *
     * @param string $email
     * @return User|mixed|object|null
     */
    public function getUserByEmail(string $email)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(["email" => $email]);
    }
}