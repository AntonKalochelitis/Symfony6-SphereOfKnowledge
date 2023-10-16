<?php

namespace App\Traits\DTO;

use Symfony\Component\Validator as Validator;

/**
 * @property string $hiring_date
 */
trait DTOHiringDate
{
    #[Validator\Constraints\NotBlank(message: "Hiring date is required")]
    #[Validator\Constraints\Length(
        min: "10",
        max: "10",
        minMessage: "Your hiring_date must be at least {{ limit }} characters long",
        maxMessage: "Your hiring_date cannot be longer than {{ limit }} characters"
    )]
    #[Validator\Constraints\Regex(
        pattern: "/\d{4}-\d{2}-\d{2}/",
        message: "Hiring date must be in the format 'Y-m-d'"
    )]
    protected string $hiring_date;

    #[Validator\Constraints\GreaterThanOrEqual('today', message: "Hiring date cannot be in the past")]
    public function getHiringDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->hiring_date);
    }

    public function setHiringDate(string $hiring_date): void
    {
        $this->hiring_date = $hiring_date;
    }
}