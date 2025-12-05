<?php

namespace RedJasmine\Payment\Application\Services\Trade\Commands;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Transformer\TradeTransformer;
use RedJasmine\Support\Exceptions\BaseException;
use Throwable;


class TradeCreateCommandHandler extends AbstractTradeCommandHandler
{
    /**
     * @param  TradeCreateCommand  $command
     *
     * @return Trade
     * @throws BaseException
     * @throws Throwable
     */
    public function handle(TradeCreateCommand $command) : Trade
    {

        $this->beginDatabaseTransaction();

        try {
            // 获取商户应用
            $merchantApp = $this->service->merchantAppRepository->find($command->merchantAppId);
            // 创建支付单
            $model = Trade::make();
            // 设置商户应用
            $model->setMerchantApp($merchantApp);
            // 填充数据
            $model = app(TradeTransformer::class)->transform($command, $model);
            // 预创建
            $model->preCreate();
            // 保存
            $this->service->repository->store($model);
            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $model;

    }

}
