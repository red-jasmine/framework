<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class UserUnbindSocialiteCommand extends Data
{
    public int $id;

    public string $provider;


}