<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Data\PromoterBindUserData;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PromoterBindUserTransformer implements TransformerInterface
{
    /**
     * @param  Data|PromoterBindUserData  $data
     * @param  Model|PromoterBindUser  $model
     *
     * @return PromoterBindUser
     */
    public function transform($data, $model) : PromoterBindUser
    {
        /**
         * @var PromoterBindUser $model
         * @var PromoterBindUserData $data
         */
        $model->promoter_id = $data->promoterId;
        $model->status = $data->status;
        $model->bind_time = $data->bindTime;
        $model->protection_time = $data->protectionTime;
        $model->expiration_time = $data->expirationTime;
        
        // 设置用户信息
        if (isset($data->user)) {
            $model->user_type = $data->user->getType();
            $model->user_id = $data->user->getID();
        }

        return $model;
    }
}