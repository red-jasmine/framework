<?php

namespace RedJasmine\Interaction\Domain\Contracts;

use RedJasmine\Interaction\Domain\Data\InteractionData;

/**
 * 资源策略
 */
interface InteractionResourceInterface
{

    /**
     * 支持的互动策略
     * @return array
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