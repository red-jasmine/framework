<?php

namespace RedJasmine\Order\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface OrderLogisticsReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * @param string $companyCode
     * @param string $logisticsNo
     * @return Collection<OrderLogistics>
     */
    public function getByLogisticsNo(string $companyCode, string $logisticsNo) : Collection;


}
