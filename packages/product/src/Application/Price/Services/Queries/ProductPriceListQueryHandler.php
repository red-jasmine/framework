<?php

namespace RedJasmine\Product\Application\Price\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class ProductPriceListQueryHandler extends QueryHandler
{
    public function __construct(
        protected PriceApplicationService $service
    ) {
    }

    /**
     * 处理变体价格列表查询
     *
     * @param ProductPriceListQuery $query
     * @return LengthAwarePaginator
     */
    public function handle(ProductPriceListQuery $query): LengthAwarePaginator
    {
        // 查询变体价格表（不是商品级价格汇总表）
        return $this->service->repository->paginate($query);
    }
}

