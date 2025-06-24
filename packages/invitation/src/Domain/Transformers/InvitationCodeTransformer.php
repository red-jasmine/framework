<?php

namespace RedJasmine\Invitation\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 邀请码转换器
 */
class InvitationCodeTransformer implements TransformerInterface
{
    /**
     * 将数据对象转换为模型
     *
     * @param Data|InvitationCodeData $data
     * @param Model|InvitationCode $model
     * @return InvitationCode
     */
    public function transform($data, $model): InvitationCode
    {
        /**
         * @var InvitationCode $model
         * @var InvitationCodeData $data
         */
        
        // 设置基本属性
        if (isset($data->code)) {
            $model->code = $data->code;
        }
        
        $model->generate_type = $data->generateType;
        $model->expired_at = $data->expiredAt;
        $model->max_usages = $data->maxUsages;
        $model->tags = $data->tags;
        $model->remarks = $data->remarks;

        // 设置邀请人信息
        if (isset($data->inviter)) {
            // 将UserInterface转换为Inviter值对象
            $inviter = new \RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter(
                get_class($data->inviter),
                $data->inviter->id,
                $data->inviter->name ?? $data->inviter->id
            );
            $model->setInviter($inviter);
        }

        return $model;
    }
} 