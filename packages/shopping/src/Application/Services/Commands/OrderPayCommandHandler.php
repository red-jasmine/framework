<?php

namespace RedJasmine\Shopping\Application\Services\Commands;

use RedJasmine\Support\Application\CommandHandler;

class OrderPayCommandHandler extends CommandHandler
{

    public function handle(OrderPayCommand $command)
    {
        // TODO
        // 订单发起支付，获取订单支付单
        // 调用 支付领域 服务  创建支付单
        // 返回 支付单 个客户端
        // 客户端  调用直接调用支付领域 创建支付单

    }

}