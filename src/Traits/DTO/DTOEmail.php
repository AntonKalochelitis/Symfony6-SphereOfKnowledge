<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

/**
 * @property $email
 */
trait DTOEmail
{
    #[Validator\Constraints\NotBlank(message: "Email is required")]
    #[Validator\Constraints\Email()]
    #[Validator\Constraints\Length(
        min: "5",
        max: "256",
        minMessage: "Your email must be at least {{ limit }} characters long",
        maxMessage: "Your email cannot be longer than {{ limit }} characters"
    )]
    #[Validator\Constraints\Type(
        type: "string",
        message: "The value {{ value }} is not a valid {{ type }}."
    )]
    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}