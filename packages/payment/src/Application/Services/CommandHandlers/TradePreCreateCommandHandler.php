<?php

namespace RedJasmine\Payment\Application\Services\CommandHandlers;

use RedJasmine\Payment\Application\Commands\Trade\TradePreCreateCommand;
use RedJasmine\Payment\Domain\Models\PaymentTrade;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use RedJasmine\Payment\Domain\Transformer\TradeTransformer;
use RedJasmine\Payment\Infrastructure\Repositories\Eloquent\MerchantAppRepository;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 创建预支付单
 */
class TradePreCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected TradeRepositoryInterface $repository,
        protected MerchantAppRepository    $merchantAppRepository
    )
    {
    }

    /**
     * @param TradePreCreateCommand $command
     * @return PaymentTrade
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(TradePreCreateCommand $command) : PaymentTrade
    {

        $this->beginDatabaseTransaction();

        try {
            // 获取商户应用
            $merchantApp = $this->merchantAppRepository->find($command->merchantAppId);

            // 创建支付单
            $model = PaymentTrade::newModel();
            // 填充数据
            $model = app(TradeTransformer::class)->transform($command, $model);
            // 设置商户应用
            $model->setMerchantApp($merchantApp);
            // 预创建
            $model->preCreate();
            // 保存
            $this->repository->store($model);
            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


        return $model;

    }

}
