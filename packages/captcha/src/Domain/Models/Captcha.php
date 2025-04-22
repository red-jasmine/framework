<?php

namespace RedJasmine\Captcha\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Captcha\Domain\Events\CaptchaCreatedEvent;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Captcha extends Model
{


    use HasSnowflakeId;

    public $incrementing = false;


    protected $dispatchesEvents = [
        'created' => CaptchaCreatedEvent::class
    ];

    protected function casts() : array
    {
        return [
            'status'      => CaptchaStatusEnum::class,
            'send_status' => CaptchaSendStatusEnum::class,
        ];
    }


    public function isAllowSend() : bool
    {
        if (!in_array($this->send_status, [CaptchaSendStatusEnum::WAIT, CaptchaSendStatusEnum::FAIL], true)) {
            return false;
        }
        if ($this->status === CaptchaStatusEnum::USED) {
            return false;
        }
        return true;
    }
}
