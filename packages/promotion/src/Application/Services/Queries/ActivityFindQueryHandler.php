<?php

namespace RedJasmine\Promotion\Application\Services\Queries;

use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 查找活动查询处理器
 */
class ActivityFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected ActivityApplicationService $service
    ) {
    }

    public function handle(ActivityFindQuery $query): ?Activity
    {
        $repository = $this->service->readRepository;
        
        // 设置包含关联
        $includes = [];
        if ($query->withProducts) {
            $includes[] = 'products';
        }
        if ($query->withParticipations) {
            $includes[] = 'participations';
        }
        
        if (!empty($includes)) {
            $repository = $repository->withQuery(function ($builder) use ($includes) {
                $builder->with($includes);
            });
        }
        
        return $repository->find($query);
    }
}
