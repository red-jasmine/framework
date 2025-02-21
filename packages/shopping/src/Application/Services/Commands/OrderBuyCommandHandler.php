<?php

namespace RedJasmine\Shopping\Application\Services\Commands;

use Illuminate\Support\Facades\Log;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Domain\Orders\OrderDomainService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

// 定义一个处理购买命令的类，继承自CommandHandler基类
class OrderBuyCommandHandler extends CommandHandler
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
     * @return \Illuminate\Support\Collection
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(ProductBuyCommand $command)
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

