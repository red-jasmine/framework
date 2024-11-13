<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;


use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


class OrderLogisticsReadRepository extends QueryBuilderReadRepository implements OrderLogisticsReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = OrderLogistics::class;

    /**
     * @param string $companyCode
     * @param string $logisticsNo
     * @return Collection<OrderLogistics>
     */
    public function getByLogisticsNo(string $companyCode, string $logisticsNo) : Collection
    {
        return $this->query()
                    ->where('logistics_company_code', $companyCode)
                    ->where('logistics_no', $logisticsNo)
                    ->get();
    }

}
