<?php

namespace RedJasmine\Region\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Region\Domain\Data\RegionData;
use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 行政区划数据转换器
 */
class RegionTransformer implements TransformerInterface
{
    /**
     * 将数据对象转换为模型
     *
     * @param RegionData $data
     * @param Region|null $model
     * @return Region
     */
    public function transform($data, $model = null): Model
    {
        if ($model === null) {
            $model = new Region();
        }

        $model->code = $data->code;
        $model->parent_code = $data->parentCode;
        $model->country_code = $data->countryCode;
        $model->type = $data->type;
        $model->name = $data->name;
        $model->region = $data->region;
        $model->level = $data->level;
        $model->phone_code = $data->phoneCode;

        return $model;
    }
}

