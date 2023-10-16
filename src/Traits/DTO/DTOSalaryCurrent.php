<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

/**
 * @property string $salary_current
 */
trait DTOSalaryCurrent
{
    #[Validator\Constraints\NotBlank(message: "Current salary is required")]
    #[Validator\Constraints\Length(
        min: "1",
        max: "256",
        minMessage: "Your salary_current must be at least {{ limit }} characters long",
        maxMessage: "Your salary_current cannot be longer than {{ limit }} characters"
    )]
    #[Validator\Constraints\Type(
        type: "numeric",
        message: "The value {{ value }} is not a valid {{ type }}."
    )]
    #[Validator\Constraints\Range(
        minMessage: "Your salary_current must be at least {{ limit }}",
        invalidMessage: "The value {{ value }} is not a valid {{ type }}",
        min: 100
    )]
    protected string $salary_current;

    public function getSalaryCurrent(): float
    {
        return $this->salary_current;
    }

    public function setSalaryCurrent(string $salary_current): void
    {
        $this->salary_current = $salary_current;
    }
}