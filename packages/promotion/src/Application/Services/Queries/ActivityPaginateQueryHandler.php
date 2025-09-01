<?php

namespace RedJasmine\Promotion\Application\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 活动分页查询处理器
 */
class ActivityPaginateQueryHandler extends QueryHandler
{
    public function __construct(
        protected ActivityApplicationService $service
    ) {
    }

    public function handle(ActivityListQuery $query): LengthAwarePaginator
    {
        $repository = $this->service->readRepository;
        
        // 应用查询条件
        if ($query->type) {
            $repository = $repository->byType($query->type->value);
        }
        
        if ($query->runningOnly) {
            $repository = $repository->running();
        }
        
        if ($query->upcomingOnly) {
            $repository = $repository->upcoming();
        }
        
        if ($query->productId) {
            $repository = $repository->byProduct($query->productId);
        }
        
        if ($query->categoryId) {
            $repository = $repository->byCategory($query->categoryId);
        }
        
        return $repository->paginate($query);
    }
}
