<?php

namespace RedJasmine\ResourceUsage\Domain\Services;

use RedJasmine\ResourceUsage\Domain\Data\UseResourceData;
use RedJasmine\ResourceUsage\Domain\Models\Enums\ResourceUsageModeEnum;
use RedJasmine\ResourceUsage\Domain\Repositories\ResourceUsageReadRepositoryInterface;

class ResourceUsageDomainService
{


    public function __construct(
        protected ResourceUsageReadRepositoryInterface $readRepository
    )
    {
    }

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

        switch ($data->mode) {
            case ResourceUsageModeEnum::CONSUME:
                $this->consume($data);
                break;
            case ResourceUsageModeEnum::SETTLE:
                // 查询资源使用模式
                $this->settle($data);
                break;
        }

    }


    protected function checkAvailableResources()
    {

    }

    protected function consume(UseResourceData $data)
    {
        // 查询可用资源
        $resource = $this->readRepository->findById();
        // 扣减资源
        $resource->deduction();
    }

    protected function settle(UseResourceData $data)
    {

    }
}