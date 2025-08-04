<?php

namespace RedJasmine\Shopping\Application\Services\Orders\Commands;


use RedJasmine\Shopping\Application\Services\HasDomainService;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Shopping\Domain\Data\PaymentTradeResult;
use RedJasmine\Shopping\Domain\Services\OrderDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;

class PayCommandHandler extends CommandHandler
{
    use HasDomainService;

    protected OrderDomainService $orderDomainService;
    // 构造函数
    // 初始化必要的服务并开始数据库事务
    public function __construct(
        protected ShoppingOrderCommandService $service,
    ) {

        $this->orderDomainService = $this->makeDomainService(OrderDomainService::class);

    }

    /**
     * @param  PayCommand  $command
     *
     * @return PaymentTradeResult
     */
    public function handle(PayCommand $command) : PaymentTradeResult
    {


        return $this->orderDomainService->pay($command->getKey());


    }


}