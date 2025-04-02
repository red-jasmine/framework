<?php

namespace RedJasmine\Interaction\Domain\Contracts;

interface InteractionTypeLimiterConfigInterface
{

    // 数量限制
    // 单次
    // 总量
    // 时间类型：秒、分、时、天、月、年
    // 数量: int 总量
    // 时间限制

    public function unique() : bool;

    /**
     * 单次数量
     * @return int
     */
    public function once() : int;


    public function total() : ?int;

    /**
     * 互动间隔时间 单位 S
     * @return int|null
     */
    public function interval() : ?int;


    public function totals() : ?array;


}