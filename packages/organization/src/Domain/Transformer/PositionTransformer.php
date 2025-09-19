<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\PositionData;
use RedJasmine\Organization\Domain\Models\Position;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PositionTransformer implements TransformerInterface
{
    /** @param PositionData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof Position) {
            $model = new Position();
        }

        $model->name = $data->name;
        $model->code = $data->code;
        $model->sequence = $data->sequence;
        $model->level = $data->level;
        $model->parent_id = $data->parentId;
        $model->description = $data->description;
        $model->status = $data->status;

        return $model;
    }
}


