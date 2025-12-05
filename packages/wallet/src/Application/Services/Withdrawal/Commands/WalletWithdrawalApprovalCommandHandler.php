<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Services\WalletWithdrawalService;
use RedJasmine\Wallet\Exceptions\WalletException;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;
use Throwable;

/**
 * @method WalletWithdrawalApplicationService getService()
 */
class WalletWithdrawalApprovalCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletWithdrawalApplicationService $service,
        protected WalletWithdrawalService $walletWithdrawalService,
    ) {
    }

    /**
     * 审批处理
     *
     * @param  WalletWithdrawalApprovalCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     * @throws WalletException
     * @throws WalletWithdrawalException
     */
    public function handle(WalletWithdrawalApprovalCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {

            $withdrawal = $this->service->repository->findByNo($command->getKey());

            $this->walletWithdrawalService->approval($withdrawal, $command);

            $this->service->repository->update($withdrawal);

            $this->commitDatabaseTransaction();
        } catch (BaseException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return true;

    }

}