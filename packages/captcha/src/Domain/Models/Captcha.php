<?php

namespace RedJasmine\Captcha\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use RedJasmine\Captcha\Domain\Events\CaptchaCreatedEvent;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\Captcha\Domain\Services\Sender\Contracts\CaptchaSenderResult;
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
            'send_time'   => 'datetime'
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


    public function setSendResult(CaptchaSenderResult $captchaSenderResult) : void
    {
        $this->send_status     = $captchaSenderResult->sendStatus;
        $this->channel         = $captchaSenderResult->channel;
        $this->channel_no      = $captchaSenderResult->channelNo;
        $this->channel_message = $captchaSenderResult->channelMessage;
        $this->send_time       = Carbon::now();
    }


    public function setVerified() : void
    {
        $this->use_time = now();
        $this->status   = CaptchaStatusEnum::USED;

    }
}
