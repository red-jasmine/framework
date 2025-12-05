<?php

namespace RedJasmine\Logistics\Domain\Transformers;

use RedJasmine\Logistics\Domain\Data\LogisticsFreightTemplateData;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class LogisticsFreightTemplateTransformer implements TransformerInterface
{

    public function __construct(
        protected LogisticsFreightTemplateStrategyTransformer $freightTemplateStrategyTransformer
    ) {
    }

    public function transform($data, $model) : LogisticsFreightTemplate
    {
        /**
         * @var LogisticsFreightTemplateData $data
         * @var LogisticsFreightTemplate $model
         */
        $model->owner       = $data->owner;
        $model->name        = $data->name;
        $model->is_free     = $data->isFree;
        $model->sort        = $data->sort;
        $model->charge_type = $data->chargeType;

        $strategies = $model->strategies;
        foreach ($strategies as $strategy) {
            $strategy->deleted_at = now();
        }


        foreach ($data->strategies as $strategy) {
            $strategyModel = $model->strategies()->newModelInstance();
            if ($strategy->id) {
                $strategyModel = $model->strategies->where('id', $strategy->id)->first();
            } else {
                $strategies[] = $strategyModel;
            }
            $strategyModel              = $this->freightTemplateStrategyTransformer->transform($strategy, $strategyModel);
            $strategyModel->deleted_at  = null;
            $strategyModel->template_id = $model->id;

        }
        $model->setRelation('strategies', $strategies);
        return $model;
    }


}