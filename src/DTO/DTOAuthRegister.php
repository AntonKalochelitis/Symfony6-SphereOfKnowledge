<?php

namespace App\DTO;

use App\Traits\DTO\DTOEmail;
use App\Traits\DTO\DTOPassword;

class DTOAuthRegister
{
    use DTOEmail;
    use DTOPassword;
}