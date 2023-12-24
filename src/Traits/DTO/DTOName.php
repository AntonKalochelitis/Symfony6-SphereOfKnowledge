<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

/**
 * @property string $name
 */
trait DTOName
{
    #[Validator\Constraints\NotBlank(message: "Name is required")]
    #[Validator\Constraints\Length(
        min: "1",
        max: "256",
        minMessage: "Name must be at least {{ limit }} characters long",
        maxMessage: "Name cannot be longer than {{ limit }} characters"
    )]
    #[Validator\Constraints\Type(
        type: "string",
        message: "The value {{ value }} is not a valid {{ type }}."
    )]
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}