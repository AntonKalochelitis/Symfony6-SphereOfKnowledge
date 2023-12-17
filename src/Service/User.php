<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class User
{
    public function __construct(
        protected UserRepository         $userRepository,
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function getUserList()
    {
        return $this->userRepository->findAll();
    }

    public function getUserBy(int $id)
    {
        return $this->userRepository->find($id);
    }
}