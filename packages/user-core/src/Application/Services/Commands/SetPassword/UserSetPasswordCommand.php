<?php

namespace RedJasmine\UserCore\Application\Services\Commands\SetStatus;

use RedJasmine\Support\Data\Data;

class UserSetPasswordCommand extends Data
{
    public int $id;

    public string $password;


}