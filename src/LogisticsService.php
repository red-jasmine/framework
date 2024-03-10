<?php

namespace RedJasmine\Logistics;

use RedJasmine\Support\Foundation\Service\Service;


class LogisticsService extends Service
{
    // Build wonderful things
    // 物流公司
    // 运费模板
    /**
     * @return LogisticsCompanyService
     */
    public function company() : LogisticsCompanyService
    {
        return app(LogisticsCompanyService::class)
            ->setOwner($this->getOwner())
            ->setOperator($this->getOperator())
            ->setClient($this->getClient());
    }

    /**
     * @return FreightTemplateService
     */
    public function template() : FreightTemplateService
    {
        return app(FreightTemplateService::class)
            ->setOwner($this->getOwner())
            ->setOperator($this->getOperator())
            ->setClient($this->getClient());
    }

}
