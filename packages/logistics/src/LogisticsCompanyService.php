<?php

namespace RedJasmine\Logistics;

use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 物流公司
 */
class LogisticsCompanyService extends Service
{


    protected static ?string $actionsConfigKey = 'red-jasmine.logistics.actions.company';

    public LogisticsService $service;

    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }

    /**
     * 物流公司
     *
     * @param string $code
     *
     * @return LogisticsCompany
     */
    public function find(string $code) : LogisticsCompany
    {
        return LogisticsCompany::where('code', $code)->firstOrFail();
    }


}
