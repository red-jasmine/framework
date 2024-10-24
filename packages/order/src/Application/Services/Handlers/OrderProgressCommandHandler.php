<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderProgressCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderProgressCommand $command
     * @return int
     * @throws AbstractException
     * @throws Throwable
     * @throws OrderException
     */
    public function handle(OrderProgressCommand $command) : int
    {
        // TODO 联动发货状态
        // 如果 进度 > 0% 设置发货中
        // 如果 进度 > 100% 设置发货完成

        $this->beginDatabaseTransaction();

        try {
            $order    = $this->find($command->id);
            $progress = $order->setProductProgress($command->orderProductId, $command->progress, $command->isAppend, $command->isAllowLess);
            $this->orderRepository->update($order);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $progress;

    }

}
