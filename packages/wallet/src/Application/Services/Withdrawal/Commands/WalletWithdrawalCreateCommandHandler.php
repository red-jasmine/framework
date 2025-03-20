<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalCommandService;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Services\WalletWithdrawalService;
use Throwable;

/**
 * @method WalletWithdrawalCommandService getService()
 */
class WalletWithdrawalCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletWithdrawalCommandService $service,
        protected WalletRepositoryInterface $walletRepository,
        protected WalletWithdrawalService $walletWithdrawalService,

    ) {
    }

    public function handle(WalletWithdrawalCreateCommand $command) : WalletWithdrawal
    {

        $this->beginDatabaseTransaction();

        try {
            $wallet = $this->walletRepository->findLock($command->id);

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