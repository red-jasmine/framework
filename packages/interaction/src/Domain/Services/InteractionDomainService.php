<?php

namespace RedJasmine\Interaction\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Interaction\Domain\Contracts\InteractionResourceStrategyInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Models\InteractionStatistic;
use RedJasmine\Interaction\Domain\Repositories\InteractionStatisticRepositoryInterface;

class InteractionDomainService
{


    public function __construct(
        protected InteractionResourceStrategyInterface $strategy,
        public InteractionStatisticRepositoryInterface $repository,
    ) {

    }

    // 初始化
    protected function init(InteractionData $data) : InteractionStatistic
    {

        return $this->repository
                   ->findByResource($data->resourceType, $data->resourceId, $data->interactionType) ??
               InteractionStatistic::make([
                   'resource_type'    => $data->resourceType,
                   'resource_id'      => $data->resourceId,
                   'interaction_type' => $data->interactionType,
                   'quantity'         => 0,
               ]);
    }

    /**
     * @param  InteractionData  $data
     *
     * @return array{statistic: InteractionStatistic, record: InteractionRecord}
     */
    public function interactive(InteractionData $data) : array
    {
        $this->strategy->validate($data);

        // 获取统计数据
        $interactionStatistic = $this->init($data);

        $interactionRecord = $this->makeRecord($data);


        return [
            'statistic' => $interactionStatistic,
            'record'    => $interactionRecord,
        ];

    }


    protected function makeRecord(InteractionData $data) : InteractionRecord
    {
        $interactionRecord                   = InteractionRecord::make();
        $interactionRecord->resource_type    = $data->resourceType;
        $interactionRecord->resource_id      = $data->resourceId;
        $interactionRecord->interaction_type = $data->interactionType;
        $interactionRecord->quantity         = $data->quantity;
        $interactionRecord->owner            = $data->user;
        $interactionRecord->interaction_time = Carbon::now();
        return $interactionRecord;
    }


}