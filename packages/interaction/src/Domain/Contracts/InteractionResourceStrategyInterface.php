<?php

namespace RedJasmine\Interaction\Domain\Contracts;

use RedJasmine\Interaction\Domain\Models\Enums\InteractionTypeEnum;

/**
 * 资源策略
 */
interface InteractionResourceStrategyInterface
{

    /**
     * 支持的互动策略
     * @return array|InteractionTypeEnum[]
     */
    public function allowInteractionType() : array;
}