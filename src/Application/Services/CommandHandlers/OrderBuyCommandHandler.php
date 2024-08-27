<?php

namespace RedJasmine\Shopping\Application\Services\CommandHandlers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Domain\Orders\OrderDomainService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;

class OrderBuyCommandHandler extends CommandHandler
{
    public function __construct(
        protected OrderDomainService $orderDomainService,

    ) {

        parent::__construct();
    }


    /**
     * @param  ProductBuyCommand  $command
     *
     * @return \Illuminate\Support\Collection
     * @throws AbstractException
     */
    public function handle(ProductBuyCommand $command)
    {

        try {
            DB::beginTransaction();

            // 下单
            $orders = $this->orderDomainService->buy($command);


            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            Log::info('下单失败:'.$exception->getMessage(), $command->toArray());
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


        return $orders;


    }

}
