<?php

namespace RedJasmine\PointsMall\Application\Services\PointProductCategory;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryReadRepositoryInterface;
use RedJasmine\PointsMall\Domain\Transformers\PointProductCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

class PointProductCategoryApplicationService extends ApplicationService
{
    public function __construct(
        public PointProductCategoryRepositoryInterface $repository,
        public PointProductCategoryReadRepositoryInterface $readRepository,
        public PointProductCategoryTransformer $transformer
    ) {
    }

    protected static string $modelClass = PointsProductCategory::class;

    public function tree(Query $query): array
    {
        return $this->readRepository->tree($query);
    }
} 