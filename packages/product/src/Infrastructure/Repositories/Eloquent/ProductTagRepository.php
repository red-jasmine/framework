<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProductTagRepository extends Repository implements ProductTagRepositoryInterface
{

    protected static string $modelClass = ProductTag::class;

}
