<?php

namespace RedJasmine\PointsMall\Application\Services\PointsProductCategory;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\PointsMall\Domain\Transformers\PointProductCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

class PointsProductCategoryApplicationService extends ApplicationService
{
    public function __construct(
        public PointProductCategoryRepositoryInterface $repository,
        public PointProductCategoryTransformer $transformer
    ) {
    }

    protected static string $modelClass = PointsProductCategory::class;

    public function tree(Query $query): array
    {
        return $this->repository->tree($query);
    }
} 