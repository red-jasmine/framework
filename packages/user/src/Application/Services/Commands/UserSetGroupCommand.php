<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class UserSetGroupCommand extends Data
{


    public int $id;

    public ?int $groupId = null;


}