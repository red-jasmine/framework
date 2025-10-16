<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Application\Commands\CommandHandler;


/**
 * @method  ProductApplicationService getService()
 */
class ProductDeleteCommandHandler extends CommandHandler
{


    public function handle(ProductDeleteCommand $command) : void
    {
        $product = $this->getService()->repository->find($command->id);

        $this->getService()->repository->delete($product);

    }

}
