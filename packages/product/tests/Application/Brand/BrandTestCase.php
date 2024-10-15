<?php

namespace RedJasmine\Product\Tests\Application\Brand;

use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;


class BrandTestCase extends ApplicationTestCase
{


    protected function brandCommandService() : BrandCommandService
    {
        return app(BrandCommandService::class);
    }

    protected function brandQueryService() : BrandQueryService
    {
        return app(BrandQueryService::class);
    }


    protected function brandRepository() : BrandRepositoryInterface
    {
        return app(BrandRepositoryInterface::class);
    }


}
