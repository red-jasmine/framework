<?php

namespace RedJasmine\Wallet\Application\Services\Commands;

use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\Application\Services\WalletCommandService;
use RedJasmine\Wallet\Domain\Models\Wallet;
use Throwable;

class WalletCreateCommandHandler extends CommandHandler
{

    public function __construct(
        protected WalletCommandService $service

    ) {
    }


    public function handle(WalletCreateCommand $command)
    {

        $this->beginDatabaseTransaction();

        try {

            $model        = Wallet::make();
            $model->owner = $command->owner;
            $model->type  = $command->type;


            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }


    }

}