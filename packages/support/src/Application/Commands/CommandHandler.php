<?php

namespace RedJasmine\Support\Application\Commands;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use RedJasmine\Support\Foundation\Service\CanUseDatabaseTransactions;

abstract class CommandHandler
{

    use HasHooks;


    use CanUseDatabaseTransactions;


    protected HandleContext $context;




}
