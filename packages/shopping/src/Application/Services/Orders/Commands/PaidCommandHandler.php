<?php

namespace RedJasmine\Shopping\Application\Services\Orders\Commands;


use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;
use RedJasmine\Shopping\Application\Services\HasDomainService;
use RedJasmine\Shopping\Application\Services\Orders\ShoppingOrderCommandService;
use RedJasmine\Shopping\Domain\Services\OrderDomainService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class PaidCommandHandler extends CommandHandler
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


    // 处理支付结果命令

    /**
     * @param  PaidCommand  $command
     *
     * @return void
     * @throws Throwable
     */
    public function handle(PaidCommand $command) : void
    {

        $this->beginDatabaseTransaction();

        try {

            $this->orderDomainService->paid(
                $command->orderNo,
                $command->orderPaymentId,
                $command,
            );


            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }


}