<?php

namespace RedJasmine\Payment\UI;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Payment\UI\CLI\Commands\QueueWorkCommand;

class PaymentUIServiceProvider extends ServiceProvider
{
    public function register() : void
    {

    }

    public function boot()
    {
        $this->commands([
            QueueWorkCommand::class
        ]);
    }
}
