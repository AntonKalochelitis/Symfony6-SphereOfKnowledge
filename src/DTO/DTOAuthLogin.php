<?php

namespace App\DTO;

use App\Traits\DTO\DTOEmail;
use App\Traits\DTO\DTOPassword;

class DTOAuthLogin
{
    use DTOEmail;
    use DTOPassword;
}