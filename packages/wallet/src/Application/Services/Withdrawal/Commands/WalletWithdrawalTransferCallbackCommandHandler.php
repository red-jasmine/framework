<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Services\WalletWithdrawalService;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;
use Throwable;

/**
 * @method WalletWithdrawalApplicationService getService()
 */
class WalletWithdrawalTransferCallbackCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletWithdrawalApplicationService $service,
        protected WalletWithdrawalService $walletWithdrawalService,
    ) {
    }

    /**
     * 审批处理
     *
     * @param  WalletWithdrawalTransferCallbackCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletWithdrawalException
     */
    public function handle(WalletWithdrawalTransferCallbackCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {

            $withdrawal = $this->service->repository->findByNo($command->getKey());

            $this->walletWithdrawalService->handleTransferCallback($withdrawal, $command);

            $this->service->repository->update($withdrawal);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return true;

    }

}