<?php

namespace RedJasmine\Message\Application\Services\Message\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class MessageMarkAsReadCommand extends Data
{
    public string $biz = 'default';

    public UserInterface $owner;

}