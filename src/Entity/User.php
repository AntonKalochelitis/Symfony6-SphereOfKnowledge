<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, PasswordHasherAwareInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"string", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(type="string")
     * )
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\Column(type:"boolean", nullable: false, options: ["default" => false])]
    private bool $validateEmail = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return strtolower($this->email);
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPasswordHasherName(): ?string
    {
        return null;
    }

    /**
     * @param bool $validateEmail
     */
    public function setValidateEmail(bool $validateEmail): void
    {
        $this->validateEmail = $validateEmail;
    }

    /**
     * @return bool
     */
    public function isValidateEmail(): bool
    {
        return $this->validateEmail;
    }
}
