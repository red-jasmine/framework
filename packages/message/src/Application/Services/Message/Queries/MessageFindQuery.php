<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Message\Queries;

use RedJasmine\Support\Application\Queries\FindQuery;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 查找消息查询
 */
class MessageFindQuery extends FindQuery
{
    public string $biz = 'default';

    public UserInterface $owner;

}
