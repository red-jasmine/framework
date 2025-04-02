<?php

namespace RedJasmine\Interaction\Domain\Services;

use RedJasmine\Interaction\Domain\Contracts\InteractionResourceInterface;
use RedJasmine\Interaction\Domain\Contracts\InteractionTypeInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;

class InteractionDomainService
{


    public function __construct(
        protected InteractionResourceInterface $resource,
        protected InteractionTypeInterface $interactionType,
        public InteractionStatisticRepositoryInterface $repository,
    ) {

    }

    // 初始化

    public function interactive(InteractionData $data) : InteractionRecord
    {
        // 资源校验
        $this->resource->validate($data);
        // 互动类型校验
        $this->interactionType->validate($data);

        return $this->interactionType->makeRecord($data);


    }


}