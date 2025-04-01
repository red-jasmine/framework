<?php

namespace RedJasmine\Interaction\Domain\Data;

use RedJasmine\Interaction\Domain\Models\Enums\InteractionTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class InteractionData extends Data
{

    public UserInterface $user;

    public InteractionTypeEnum $interactionType;

    public string $resourceId;

    public string $resourceType;

    /**
     * 互动量
     * @var int
     */
    public int $quantity = 1;


}