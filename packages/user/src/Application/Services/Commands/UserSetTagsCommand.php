<?php

namespace RedJasmine\User\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class UserSetTagsCommand extends Data
{


    public int $id;

    public array $tags = [];


}