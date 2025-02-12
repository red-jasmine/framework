<?php

namespace RedJasmine\ResourceUsage\Domain\Services;

use RedJasmine\ResourceUsage\Domain\Data\UseResourceData;
use RedJasmine\ResourceUsage\Domain\Models\Enums\ResourceUsageModeEnum;

class ResourceUsageDomainService
{


    public function allowUse()
    {

    }

    public function grant()
    {
        //

    }

    // 使用
    public function use(UseResourceData $data)
    {
        // 消费模式
        if ($data->mode === ResourceUsageModeEnum::CONSUME) {
            // 查询资源使用模式

        }

        // 查询有效资源包
        // 扣资源量
    }
}