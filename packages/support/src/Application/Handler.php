<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\Hook\HasHooks;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;

abstract class Handler
{
    use HasHooks;


    use CanUseDatabaseTransactions;


    protected HandleContext $context;

    protected function initHandleContext() : void
    {
        if (!isset($this->context)) {
            $this->context = new HandleContext();
        }

    }
}