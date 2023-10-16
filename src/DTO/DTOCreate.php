<?php

namespace App\DTO;

use App\Traits\DTO\DTOEmail;
use App\Traits\DTO\DTOFirstName;
use App\Traits\DTO\DTOHiringDate;
use App\Traits\DTO\DTOLastName;
use App\Traits\DTO\DTOSalaryCurrent;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $hiring_date
 * @property string $salary_current
 */
class DTOCreate
{
    use DTOFirstName;
    use DTOLastName;
    use DTOEmail;
    use DTOHiringDate;
    use DTOSalaryCurrent;
}