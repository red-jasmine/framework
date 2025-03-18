<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use RedJasmine\Product\Application\Product\Services\ProductCommandService;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;


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
