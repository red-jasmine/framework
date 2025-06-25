<?php

namespace RedJasmine\Invitation\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class InvitationCodeTransformer implements TransformerInterface
{
    /**
     * @param  InvitationCodeData  $data
     * @param  InvitationCode  $model
     *
     * @return InvitationCode
     */
    public function transform($data, $model) : Model
    {
        $model->code        = $data->code;
        $model->code_type   = $data->codeType;
        $model->status      = $data->status;
        $model->max_usage   = $data->maxUsage;
        $model->used_count  = $data->usedCount;
        $model->expired_at  = $data->expiredAt;
        $model->extra_data  = $data->extraData;
        $model->description = $data->description;
        $model->owner     = $data->owner;

        return $model;
    }
} 