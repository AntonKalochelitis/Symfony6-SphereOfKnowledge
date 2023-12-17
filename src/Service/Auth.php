<?php

namespace App\Service;

use App\DTO\DTOAuthLogin;
use App\DTO\DTOAuthRegister;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class Auth
{
    public function __construct(
        protected EntityManagerInterface      $entityManager,
        protected JWTTokenManagerInterface    $jwtManager,
        protected UserPasswordHasherInterface $passwordHasher,
        protected UserRepository              $userRepository,
        protected TokenStorageInterface       $tokenStorage
    )
    {
    }

    public function register(DTOAuthRegister $dto): ?User
    {
        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $dto->getPassword()
        );

        $user->setEmail($dto->getEmail());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function login(DTOAuthLogin $dto): ?User
    {
        $user = $this->userRepository->findOneBy([
            'email' => $dto->getEmail(),
        ]);

        $this->jwtManager->

        $user->getPassword();

        return $user;
    }
}