<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class UserSetPasswordCommand extends Data
{
    public int $id;

    public string $password;


}