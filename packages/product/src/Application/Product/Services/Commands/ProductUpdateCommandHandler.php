<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Application\Commands\CommandContext;

/**
 * @method  ProductApplicationService getService()
 * @property
 */
class ProductUpdateCommandHandler extends ProductCommandHandler
{
    protected string $name = 'update';

    protected function resolve(CommandContext $context) : Model
    {
        /**
         * @var ProductUpdateCommand $command
         */
        $command = $context->getCommand();
        $product = $this->service->repository->find($command->getKey());
        $product->setRelation('variants', $product->variants()->withTrashed()->get());
        // 这里的逻辑应该 放到领域层
        $product->variants->each(function ($sku) {
            $sku->setDeleted();
        });
        return $product;
    }




}
