<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Application\Services\Message\Queries;

use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息列表查询
 */
class MessagePaginateQuery extends PaginateQuery
{

    public string $biz        = 'default';

    public UserInterface $owner;
    public ?int   $categoryId = null;



    #[WithCast(EnumCast::class, MessagePriorityEnum::class)]
    public ?MessagePriorityEnum $priority = null;


    #[WithCast(EnumCast::class, MessageStatusEnum::class)]
    public ?MessageStatusEnum $status;


}
