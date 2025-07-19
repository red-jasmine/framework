<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Services\WalletWithdrawalService;
use RedJasmine\Wallet\Exceptions\WalletException;
use Throwable;

/**
 * @method WalletWithdrawalApplicationService getService()
 */
class WalletWithdrawalCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletWithdrawalApplicationService $service,
        protected WalletRepositoryInterface $walletRepository,
        protected WalletWithdrawalService $walletWithdrawalService,

    ) {
    }

    /**
     * @param  WalletWithdrawalCreateCommand  $command
     *
     * @return WalletWithdrawal
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function handle(WalletWithdrawalCreateCommand $command) : WalletWithdrawal
    {

        $this->beginDatabaseTransaction();

        try {
            $wallet = $this->walletRepository->findLock($command->getKey());

            $withdrawal = $this->walletWithdrawalService->withdrawal($wallet, $command);

            $this->service->repository->store($withdrawal);

            $this->walletRepository->update($wallet);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

        return $withdrawal;

    }

}