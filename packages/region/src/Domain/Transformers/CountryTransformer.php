<?php

namespace RedJasmine\Region\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Region\Domain\Data\CountryData;
use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 国家数据转换器
 */
class CountryTransformer implements TransformerInterface
{
    /**
     * 将数据对象转换为模型
     *
     * @param CountryData $data
     * @param Country|null $model
     * @return Country
     */
    public function transform($data, $model = null): Model
    {
        if ($model === null) {
            $model = new Country();
        }

        $model->code = $data->code;
        $model->iso_alpha_3 = $data->isoAlpha3;
        $model->name = $data->name;
        $model->native = $data->native;
        $model->region = $data->region;
        $model->currency = $data->currency;
        $model->phone_code = $data->phoneCode;
        $model->longitude = $data->longitude;
        $model->latitude = $data->latitude;
        $model->timezones = $data->timezones;
        $model->translations = $data->translations;

        return $model;
    }
}

