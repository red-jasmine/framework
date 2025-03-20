<?php

namespace RedJasmine\Wallet\Application\Services\Wallet\Commands;

use RedJasmine\Support\Application\CommandHandlers\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Services\WalletService;
use Throwable;

class WalletTransactionCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletRepositoryInterface $repository,
        protected WalletService $walletService

    ) {
    }


    public function handle(WalletTransactionCommand $command) : WalletTransaction
    {

        $this->beginDatabaseTransaction();

        try {
            $model = $this->repository->findLock($command->id);


            $wallet = $this->walletService->transaction($model, $command);


            $this->repository->update($wallet);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return $wallet->transactions->first();


    }

}