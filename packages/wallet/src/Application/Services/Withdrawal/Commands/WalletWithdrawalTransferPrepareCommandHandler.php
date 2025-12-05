<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\BaseException;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Services\WalletWithdrawalService;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;
use Throwable;


class WalletWithdrawalTransferPrepareCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletWithdrawalApplicationService $service,
        protected WalletWithdrawalService $walletWithdrawalService,
    ) {
    }

    /**
     *
     *
     * @param  WalletWithdrawalTransferPrepareCommand  $command
     *
     * @return bool
     * @throws BaseException
     * @throws Throwable
     * @throws WalletWithdrawalException
     */
    public function handle(WalletWithdrawalTransferPrepareCommand $command) : bool
    {

        $this->beginDatabaseTransaction();

        try {

            $withdrawal = $this->service->repository->findByNoLock($command->getKey());

            $this->walletWithdrawalService->createTransfer($withdrawal);

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