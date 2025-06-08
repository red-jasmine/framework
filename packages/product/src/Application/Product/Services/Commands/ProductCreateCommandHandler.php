<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use Throwable;

/**
 * @method  ProductApplicationService getService()
 */
class ProductCreateCommandHandler extends ProductCommandHandler
{


    // 需要组合 品牌服务、分类服务、卖家分类服务、属性服务

    /**
     * 处理产品创建命令
     *
     * @param  ProductCreateCommand  $command  产品创建命令对象，包含创建产品所需的信息
     *
     * @return Product 返回创建完成的产品对象
     * @throws Throwable 如果处理过程中发生错误，将抛出异常
     */
    public function handle(ProductCreateCommand $command) : Product
    {



        // 初始化产品模型实例
        /**
         * @var $product Product
         */
        $product = Product::make([]);

        // 开始数据库事务
        $this->beginDatabaseTransaction();

        try {
            // 设置当前处理的产品模型
            // 执行核心处理逻辑

            $this->service->hook('create.validate',$command, fn() => $this->validate($command));

            $product =  $this->service->hook('create.fill',$command, fn() => $this->productTransformer->transform($product, $command));


            // 保存产品到数据库
            $this->service->repository->store($product);

            // 设置库存
            $this->handleStock($product, $command);

            // 提交数据库事务
            $this->commitDatabaseTransaction();
            return $product;
        } catch (Throwable $exception) {
            // 如果发生异常，回滚数据库事务
            $this->rollBackDatabaseTransaction();
            throw $exception;
        }

    }




}
