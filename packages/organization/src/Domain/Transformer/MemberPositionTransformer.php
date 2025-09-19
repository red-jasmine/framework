<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\MemberPositionData;
use RedJasmine\Organization\Domain\Models\MemberPosition;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class MemberPositionTransformer implements TransformerInterface
{
    /** @param MemberPositionData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof MemberPosition) {
            $model = new MemberPosition();
        }

        $model->member_id = $data->memberId;
        $model->position_id = $data->positionId;
        $model->started_at = $data->startedAt;
        $model->ended_at = $data->endedAt;

        return $model;
    }
}


