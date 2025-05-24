<?php

namespace RedJasmine\Logistics\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Logistics\Domain\Data\LogisticsFreightTemplateStrategyData;
use RedJasmine\Logistics\Domain\Models\Extensions\LogisticsFreightTemplateStrategy;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class LogisticsFreightTemplateStrategyTransformer implements TransformerInterface
{
    public function transform(Data $data, ?Model $model = null) : ?Model
    {
        /**
         * @var LogisticsFreightTemplateStrategyData $data
         * @var LogisticsFreightTemplateStrategy $model
         */

        if ($data->isAllRegions) {
            $model->setRelation('regions', collect([]));
        } else {
            $model->setRelation('regions', collect($data->regions));
        }
        $model->is_all_regions    = $data->isAllRegions;
        $model->type              = $data->type;
        $model->standard_fee      = $data->standardFee;
        $model->standard_quantity = $data->standardQuantity;
        $model->extra_fee         = $data->extraFee;
        $model->extra_quantity    = $data->extraQuantity;


        return $model;
    }


}