<?php

namespace RedJasmine\Shopping\Application\Services\Commands;

use RedJasmine\Shopping\Application\UserCases\Commands\OrderBuyCommand;
use RedJasmine\Shopping\Domain\Orders\OrderDomainService;
use RedJasmine\Support\Application\CommandHandler;

class ProductCalculateCommandHandler extends CommandHandler
{
    public function __construct(
        protected OrderDomainService $orderDomainService,

    ) {
        parent::__construct();
    }


    public function handle(ProductBuyCommand $command)
    {
        $orders = $this->orderDomainService->calculates($command);

        return $orders;


    }

}
