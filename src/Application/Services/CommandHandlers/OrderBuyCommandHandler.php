<?php

namespace RedJasmine\Shopping\Application\Services\CommandHandlers;

use DB;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductBuyCommand;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderBuyCommandHandler extends CommandHandler
{
    public function __construct(
        protected ProductCommandService $productCommandService,
        protected ProductQueryService   $productQueryService,
        protected OrderCommandService   $orderCommandService,
    )
    {
        parent::__construct();
    }


    public function handle(ProductBuyCommand $command)
    {


        // 商品验证
        $product = $this->productQueryService->find($command->productId);

        // 验证库存

        // 计算邮费

        // 计算优惠


        // 创建订单

        // 合单支付

        try {
            DB::beginTransaction();

            // 核销优惠券
            // 减库存
            // 创建订单


            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


        $this->orderCommandService->create($command);
    }

}
