<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\PointsExchangeOrderApplicationService;
use RedJasmine\PointsMall\Domain\Data\PointsExchangeOrderData;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Foundation\Data\Data;
use Throwable;


/**
 *
 * @property PointsExchangeOrderApplicationService $service
 */
class PointsExchangeOrderCreateCommandHandler extends CreateCommandHandler
{

    /**
     * 处理命令对象
     *
     * @param  PointsExchangeOrderCreateCommand  $command  被处理的命令对象
     *
     * @return Model|null 返回处理后的模型对象或其他相关结果
     * @throws Throwable
     */
    public function handle(Data $command) : ?Model
    {
        // 转换 命令对象
        // 积分商品、积分钱包、用户地址、积分商品对用的产品

        $pointsProduct = $this->service->pointsProductRepository->find($command->pointsProductId);

        $newCommand               = new PointsExchangeOrderData;
        $newCommand->buyer        = $command->user;
        $newCommand->quantity     = $command->quantity;
        $newCommand->skuId        = $command->productSkuId;
        $newCommand->pointsProduct = $pointsProduct;



        $this->context->setCommand($newCommand);
        $command = $newCommand;
        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {

            $model = $this->service->pointsExchangeService->exchange($command);

            // 初始化模型
            $this->context->setModel($model);
            // 对数据进行验证
            //$this->callHook('validate', $this->context, fn() => $this->validate($this->context));
            // 填充模型
            //$this->callHook('fill', $this->context, fn() => $this->fill($this->context));
            // 存储模型到仓库
            $this->callHook('save', $this->context, fn() => $this->save($this->context));
            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->context->getModel();


    }



}