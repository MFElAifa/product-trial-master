<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,

    ) {}

    public function saveUser(User $user): ?User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
