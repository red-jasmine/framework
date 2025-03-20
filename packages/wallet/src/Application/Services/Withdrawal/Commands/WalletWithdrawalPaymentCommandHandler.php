<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalCommandService;
use RedJasmine\Wallet\Domain\Services\WalletWithdrawalService;
use RedJasmine\Wallet\Exceptions\WalletException;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;
use Throwable;

/**
 * @method WalletWithdrawalCommandService getService()
 */
class WalletWithdrawalPaymentCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletWithdrawalCommandService $service,
        protected WalletWithdrawalService $walletWithdrawalService,
    ) {
    }

    /**
     * 审批处理
     *
     * @param  WalletWithdrawalApprovalCommand  $command
     *
     * @return bool
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     * @throws WalletWithdrawalException
     */
    public function handle(WalletWithdrawalPaymentCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {

            $withdrawal = $this->service->repository->findByNo($command->withdrawalNo);

            $this->walletWithdrawalService->payment($withdrawal, $command);

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