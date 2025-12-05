<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Data\PromoterData;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Foundation\Data\Data;

class PromoterTransformer implements TransformerInterface
{
    /**
     * @param  Data|PromoterData  $data
     * @param  Model|Promoter  $model
     *
     * @return Promoter
     */
    public function transform($data, $model) : Promoter
    {
        /**
         * @var Promoter $model
         * @var PromoterData $data
         */
        $model->level     = $data->level;
        $model->parent_id = $data->parentId;
        $model->team_id   = $data->teamId;
        $model->group_id  = $data->groupId;
        $model->status    = $data->status;
        $model->remarks   = $data->remarks;

        // 设置所属人
        if (isset($data->owner)) {
            $model->setOwner($data->owner);
        }

        return $model;
    }
}