<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Message\Queries;


use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 * 消息统计查询
 */
class MessageStatisticsQuery extends Query
{

    public string        $biz = 'default';
    public UserInterface $owner;

}
