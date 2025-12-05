<?php

namespace RedJasmine\Interaction\Domain\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;

class InteractionData extends Data
{

    public UserInterface $user;

    public string $interactionType;

    public string $resourceId;

    public string $resourceType;

    /**
     * 互动量
     * @var int
     */
    public int $quantity = 1;

    /**
     * @var array 扩展字段 由互动类型确认
     */
    public array $extra = [];

}