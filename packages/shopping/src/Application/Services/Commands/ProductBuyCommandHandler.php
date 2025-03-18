<?php

namespace RedJasmine\Shopping\Application\Services\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Domain\Orders\OrderDomainService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

// 定义一个处理购买命令的类，继承自CommandHandler基类
class ProductBuyCommandHandler extends CommandHandler
{
    // 构造函数
    // 初始化必要的服务并开始数据库事务
    public function __construct(
        protected OrderDomainService $orderDomainService,
    ) {

    }


    /**
     * @param  ProductBuyCommand  $command
     *
     * @return Collection<Order>
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(ProductBuyCommand $command) : Collection
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

