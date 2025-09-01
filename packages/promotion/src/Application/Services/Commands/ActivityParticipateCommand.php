<?php

namespace RedJasmine\Promotion\Application\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 参与活动命令
 */
class ActivityParticipateCommand extends Data
{
    public int $activityId;
    public UserInterface $user;
    public array $participationData = [];
    
    // 参与数据可能包含的字段示例：
    // - product_id: 商品ID
    // - quantity: 数量
    // - group_id: 团ID（拼团活动）
    // - is_leader: 是否开团（拼团活动）
    // - total_amount: 总金额（满减活动）
    // - product_ids: 商品ID数组（批量参与）
    // - quantities: 数量数组（批量参与）
}
