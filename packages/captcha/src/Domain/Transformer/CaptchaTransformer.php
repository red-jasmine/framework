<?php

namespace RedJasmine\Captcha\Domain\Transformer;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class CaptchaTransformer implements TransformerInterface
{
    /**
     * @param  CaptchaData  $data
     * @param  Captcha  $model
     *
     * @return Captcha
     */
    public function transform($data, $model) : Captcha
    {
        $expTime = now()->addMinutes($data->expMinutes);
        // 存储数据
        $model->app             = $data->app;
        $model->type            = $data->type;
        $model->method          = $data->method;
        $model->notifiable_type = $data->notifiableType;
        $model->notifiable_id   = $data->notifiableId;
        $model->code            = mt_rand(00000, 999999);
        $model->status          = CaptchaStatusEnum::WAIT;
        $model->send_status     = CaptchaSendStatusEnum::WAIT;
        $model->exp_time        = $expTime;

        return $model;

    }


}