<?php

namespace RedJasmine\Wallet\Application\Services\Wallet\Commands;


use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Services\WalletService;
use RedJasmine\Wallet\Exceptions\WalletException;
use Throwable;

class WalletTransactionCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletApplicationService $service,
        protected WalletService $walletService

    ) {
    }


    /**
     * @param  WalletTransactionCommand  $command
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function handle(WalletTransactionCommand $command) : WalletTransaction
    {

        $this->beginDatabaseTransaction();

        try {
            $model = $this->service->repository->findLock($command->id);


            $wallet = $this->walletService->transaction($model, $command);


            $this->service->repository->update($wallet);

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