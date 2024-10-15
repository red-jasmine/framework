<?php

namespace RedJasmine\Product\Tests\Application\Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyGroupFaker;

class ProductPropertyTestCase extends ApplicationTestCase
{

    protected function groupCommandService() : ProductPropertyGroupCommandService
    {
        return app(ProductPropertyGroupCommandService::class);
    }


    protected function groupRepository() : ProductPropertyGroupRepositoryInterface
    {
        return app(ProductPropertyGroupRepositoryInterface::class);
    }


    public function propertyCommandService() : ProductPropertyCommandService
    {
        return app(ProductPropertyCommandService::class);
    }


    public function propertyRepository() : ProductPropertyRepositoryInterface
    {
        return app(ProductPropertyRepositoryInterface::class);
    }


    public function valueCommandService() : ProductPropertyValueCommandService
    {
        return app(ProductPropertyValueCommandService::class);
    }


    public function valueRepository() : ProductPropertyValueRepositoryInterface
    {
        return app(ProductPropertyValueRepositoryInterface::class);
    }


    protected function createGroup() : ProductPropertyGroup
    {
        $command = (new ProductPropertyGroupFaker())->createCommand(['name'=>'测试']);

        return $this->groupCommandService()->create($command);

    }


}
