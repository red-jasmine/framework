<?php

namespace RedJasmine\Product\Application\Product\Services\CommandHandlers;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Product\Application\Product\UserCases\Commands\ProductDeleteCommand;
use RedJasmine\Support\Application\CommandHandler;


/**
 * @method  ProductCommandService getService()
 */
class ProductDeleteCommandHandler extends CommandHandler
{


    public function handle(ProductDeleteCommand $command) : void
    {
        $product = $this->getService()->getRepository()->find($command->id);

        $this->getService()->getRepository()->delete($product);

    }

}
