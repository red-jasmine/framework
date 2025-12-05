<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Data\PromoterLevelData;
use RedJasmine\Distribution\Domain\Models\PromoterLevel;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Foundation\Data\Data;

class PromoterLevelTransformer implements TransformerInterface
{
    /**
     * @param  Data|PromoterLevelData  $data
     * @param  Model|PromoterLevel  $model
     *
     * @return PromoterLevel
     */
    public function transform($data, $model) : PromoterLevel
    {
        /**
         * @var PromoterLevel $model
         * @var PromoterLevelData $data
         */
        $model->level = $data->level;
        $model->name = $data->name;
        $model->description = $data->description;
        $model->icon = $data->icon;
        $model->image = $data->image;
        $model->upgrades = $data->upgrades;
        $model->keeps = $data->keeps;
        $model->product_ratio = $data->productRatio;
        $model->parent_ratio = $data->parentRatio;
        $model->benefits = $data->benefits;

        return $model;
    }
}