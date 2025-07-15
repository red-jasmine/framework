<?php

namespace RedJasmine\Shopping\Application\Services\Orders\Commands;

use Illuminate\Support\Facades\Log;
use RedJasmine\Ecommerce\Domain\Data\OrdersData;
use RedJasmine\Shopping\Application\Services\HasDomainService;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Domain\Services\OrderDomainService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

// 定义一个处理购买命令的类，继承自CommandHandler基类
class BuyCommandHandler extends CommandHandler
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
     * @param  BuyCommand  $command
     *
     * @return OrdersData
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(BuyCommand $command) : OrdersData
    {
        $this->beginDatabaseTransaction();
        try {
            $orders = $this->orderDomainService->buy($command);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            Log::info('下单失败:'.$exception->getMessage(), $command->toArray());
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $orders;
    }
}

