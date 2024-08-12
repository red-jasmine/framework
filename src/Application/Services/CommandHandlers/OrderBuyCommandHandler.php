<?php

namespace RedJasmine\Shopping\Application\Services\CommandHandlers;

use DB;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\Services\StockQueryService;
use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Shopping\Application\Services\OrderCommandService;
use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Application\UserCases\Commands\ProductBuyCommand;
use RedJasmine\Shopping\Domain\Services\OrderDomainService;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderBuyCommandHandler extends CommandHandler
{
    public function __construct(
        protected ProductCommandService $productCommandService,
        protected ProductQueryService   $productQueryService,
        protected StockQueryService     $stockQueryService,
        protected StockCommandService   $stockCommandService,
        protected OrderCommandService   $orderCommandService,
        protected OrderDomainService   $orderDomainService
    )
    {
        parent::__construct();
    }


    public function handle(ProductBuyCommand $command)
    {


        $this->orderDomainService->product($command);
        dd($command);


        // 单个订单处理流程

        $products = [];

        foreach ($command->products as $productData) {
            $product = $this->productQueryService->find($productData->productId);
            $stock   = $this->stockQueryService->find($productData->skuId);
        }



        // 验证库存


        // 计算邮费 TODO

        // 计算优惠 TODO


        // 生成 订单的 Command

        $orderCreateCommand         = new OrderCreateCommand;
        $orderCreateCommand->seller = $product->owner;
        $orderCreateCommand->buyer  = $command->buyer;

        dd($orderCreateCommand);

        // 创建订单

        // 合单支付

        try {
            DB::beginTransaction();
            // 创建订单

            $order = $this->orderCommandService->create($orderCreateCommand);

            // 减库存

            $orderId = $this->getService()->buildId();

            // 减库存
            $stockCommand = StockCommand::from(
                [
                    'productId'    => $command->productId,
                    'skuId'        => $command->skuId,
                    'stock'        => $command->quantity,
                    'changeType'   => 'sale',
                    'changeDetail' => $orderId
                ]
            );

            // 锁库存
            $this->stockCommandService->sub($stockCommand);

            // 创建订单

            // 构建订单


            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }


    }

}
