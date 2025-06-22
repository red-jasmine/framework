<?php

namespace RedJasmine\Distribution\Application\PromoterApply\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Distribution\Application\PromoterApply\Services\PromoterApplyApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 分销员申请分页查询处理器
 */
class PromoterApplyPaginateQueryHandler extends QueryHandler
{
    public function __construct(
        protected PromoterApplyApplicationService $service
    ) {
    }

    /**
     * 处理分页查询
     *
     * @param PromoterApplyPaginateQuery $query
     * @return LengthAwarePaginator
     */
    public function handle(PromoterApplyPaginateQuery $query): LengthAwarePaginator
    {
        // 使用标准的分页查询方式
        return $this->service->readRepository->paginate($query);
    }

}