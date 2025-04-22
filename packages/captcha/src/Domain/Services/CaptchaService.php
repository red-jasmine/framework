<?php

namespace RedJasmine\Captcha\Domain\Services;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\Captcha\Exceptions\CaptchaException;
use RedJasmine\Captcha\Jobs\CaptchaSendJob;
use Throwable;

class CaptchaService
{

    // Build wonderful things

    /**
     * 发送验证码
     *
     * @param  array{notifiableType:NotifiableTypeEnum,notifiable:string,type:string,app:string,code:string}  $data
     *
     * @return bool
     * @throws CaptchaException
     */
    public function check(array $data) : bool
    {
        return $this->verify($data['notifiableType'], $data['notifiable'], $data['type'], $data['app'] ?? null,
            (string) ($data['code'] ?? ''));
    }


    /**
     * @param  NotifiableTypeEnum  $notifiableType
     * @param  string  $notifiable
     * @param  string  $type
     * @param  string  $app
     * @param  string  $code
     *
     * @return bool
     * @throws CaptchaException
     */
    protected function verify(
        NotifiableTypeEnum $notifiableType,
        string $notifiable,
        string $type,
        string $app = 'app',
        string $code
    ) : bool {
        if (blank($code)) {
            throw new CaptchaException('验证码不能为空', CaptchaException::SEND_ERROR);
        }
        // 查询最近的一条
        $query       = CaptchaService::where('notifiable', $notifiable)
                                     ->where('notifiable_type', $notifiableType->value)
                                     ->where('type', $type)
                                     ->where('app', $app)
                                     ->where('code', (string) $code)
                                     ->where('status', CaptchaStatusEnum::WAIT->value);
        $captchaCode = $query->first();
        if (blank($captchaCode)) {
            throw new CaptchaException('验证码错误', CaptchaException::SEND_ERROR);
        }
        // 验证状态
        if ($captchaCode->status !== CaptchaStatusEnum::WAIT) {
            throw new CaptchaException('验证码状态错误', CaptchaException::SEND_ERROR);
        }
        // 验证过期时间
        if (now() > $captchaCode->exp_time) {
            throw new CaptchaException('验证码已过期,请重新发送', CaptchaException::SEND_ERROR);
        }
        $captchaCode->use_time = now();
        $captchaCode->status   = CaptchaStatusEnum::USED;
        $captchaCode->save();
        return true;

    }






}
