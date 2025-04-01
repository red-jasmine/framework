<?php

namespace RedJasmine\Interaction\Domain\Contracts;

use RedJasmine\Interaction\Domain\Data\InteractionData;
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


    /**
     * 判断是否允许互动
     * 验证资源是否存在
     * 验证资源是否允许互动
     * 验证用户权限等
     *
     * @param  InteractionData  $data
     *
     * @return bool
     */
    public function validate(InteractionData $data) : bool;
}