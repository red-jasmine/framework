<?php

namespace RedJasmine\Logistics;

use RedJasmine\Logistics\Models\LogisticsCompany;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 物流公司
 */
class LogisticsCompanyService extends Service
{


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
