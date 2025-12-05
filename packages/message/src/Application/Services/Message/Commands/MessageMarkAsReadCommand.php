<?php

namespace RedJasmine\Message\Application\Services\Message\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class MessageMarkAsReadCommand extends Data
{
    public string $biz = 'default';

    public UserInterface $owner;

}