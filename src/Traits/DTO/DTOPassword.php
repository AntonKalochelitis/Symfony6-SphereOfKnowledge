<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

/**
 * @property $password
 */
trait DTOPassword
{
    #[Validator\Constraints\NotBlank(message: "Password is required")]
    #[Validator\Constraints\Type(
        type: "string",
        message: "The value {{ value }} is not a valid {{ type }}."
    )]
    #[Validator\Constraints\Length(
        min: "6",
        max: "256",
        minMessage: "Your email must be at least {{ limit }} characters long",
        maxMessage: "Your email cannot be longer than {{ limit }} characters"
    )]
    #[Validator\Constraints\Regex(
        pattern: "/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*\W).+$/",
        message: "Password should contain at least one digit, one uppercase letter, one lowercase letter, and one special character."
    )]
    protected string $password;

    public function getPassword():string
    {
        return $this->password;
    }

    public function setPassword(string $password):void
    {
        $this->password = $password;
    }
}