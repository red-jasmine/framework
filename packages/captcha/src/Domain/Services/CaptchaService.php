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

    public function create(CaptchaData $notifiableData, int $expMinutes = 10) : Captcha
    {

        $code = $this->buildCode($notifiableData->notifiableType, $notifiableData->notifiableId, $notifiableData->type);

        $expTime = now()->addMinutes($expMinutes);
        // 存储数据
        $captcha = Captcha::make();

        $captcha->app             = $notifiableData->app;
        $captcha->type            = $notifiableData->type;
        $captcha->notifiable_type = $notifiableData->notifiableType;
        $captcha->notifiable_id   = $notifiableData->notifiableId;
        $captcha->code            = $code;
        $captcha->status          = CaptchaStatusEnum::WAIT;
        $captcha->send_status     = CaptchaSendStatusEnum::WAIT;
        $captcha->exp_time        = $expTime;

        return $captcha;
    }

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

    /**
     * 发送验证码
     *
     * @param  array{notifiableType:NotifiableTypeEnum,notifiable:string,type:string,app:string,expMinutes:int}  $data
     *
     * @return CaptchaService
     * @throws CaptchaException
     */
    public function captcha(array $data) : CaptchaService
    {
        return $this->notify($data['notifiableType'], $data['notifiable'], $data['type'], $data['app'] ?? null,
            $data['expMinutes'] ?? null);
    }

    /**
     * 生成验证码
     *
     * @param  NotifiableTypeEnum  $notifiableType
     * @param  string  $notifiable
     * @param  string  $type
     *
     * @return int
     */
    protected function buildCode(NotifiableTypeEnum $notifiableType, string $notifiable, string $type) : int
    {
        return mt_rand(1000, 9999);
    }


    /**
     * 发送状态码
     *
     * @param  NotifiableTypeEnum  $notifiableType
     * @param  string  $notifiable
     * @param  string  $type
     * @param  string  $app
     * @param  int  $expMinutes
     *
     * @return CaptchaService
     * @throws CaptchaException
     */
    public function notify(
        NotifiableTypeEnum $notifiableType,
        string $notifiable,
        string $type,
        string $app = 'app',
        int $expMinutes = 10
    ) : CaptchaService {
        $code    = $this->buildCode($notifiableType, $notifiable, $type);
        $expTime = now()->addMinutes($expMinutes);
        // TODO  频率控制 类型频率控制、应用频率控制
        // 存储数据
        try {
            $captchaCode = Captcha::make();

            $captchaCode->app             = $app;
            $captchaCode->type            = $type;
            $captchaCode->notifiable_type = $notifiableType;
            $captchaCode->notifiable_id   = $notifiable;
            $captchaCode->code            = $code;
            $captchaCode->status          = CaptchaStatusEnum::WAIT;
            $captchaCode->send_status     = CaptchaSendStatusEnum::WAIT;
            $captchaCode->exp_time        = $expTime;
            $captchaCode->save();
        } catch (Throwable $throwable) {
            report($throwable);
            throw new CaptchaException('发送失败', CaptchaException::SEND_ERROR);
        }

        // 队列推送
        CaptchaSendJob::dispatch($captchaCode->id);

        return $captchaCode;

    }


    /**
     * 发送
     *
     * @param  int  $id
     *
     * @return void
     * @throws CaptchaException
     */
    public function send(int $id) : void
    {
        $captchaCode = CaptchaService::findOrFail($id);
        $this->isAllowSend($captchaCode);
        $captchaCode->send_status = CaptchaSendStatusEnum::SENDING;
        $captchaCode->save();
        try {
            switch ($captchaCode->notifiable_type) {
                case NotifiableTypeEnum::MOBILE:
                    $this->sms($captchaCode->notifiable, $captchaCode->code);
                    break;
                case NotifiableTypeEnum::EMAIL:
                    $this->email($captchaCode->notifiable, $captchaCode->code);
                    break;
            }
            // 发送成功
            $captchaCode->send_status  = CaptchaSendStatusEnum::SEND;
            $captchaCode->send_time    = now();
            $captchaCode->send_channel = 'test';
        } catch (Throwable $throwable) {

            $captchaCode->send_status = CaptchaSendStatusEnum::FAIL;
        }

        $captchaCode->save();
    }


    /**
     *  TODO 封装其他发送
     *
     * @param  string  $mobile
     * @param  string  $code
     *
     * @return mixed
     * @throws CaptchaException
     */
    public function sms(string $mobile, string $code) : mixed
    {

        try {
            return app('easy-sms')->send($mobile, [
                'content'  => '您的验证码为：${code}，请勿泄露于他人！',
                'template' => 'SMS_276355183',
                'data'     => [
                    'code' => $code
                ],
            ]);
        } catch (Throwable $throwable) {
            // 发送
            report($throwable);
            throw new CaptchaException('推送失败', CaptchaException::SEND_FAIL);
        }

    }

    public function email(string $mobile, string $code)
    {
    }

    /**
     * 是否允许发送
     *
     * @param  CaptchaService  $captchaCode
     *
     * @return bool
     * @throws CaptchaException
     */
    protected function isAllowSend(CaptchaService $captchaCode) : bool
    {
        if (!in_array($captchaCode->send_status, [CaptchaSendStatusEnum::WAIT, CaptchaSendStatusEnum::FAIL], true)) {
            throw new CaptchaException('发送状态错误', CaptchaException::SEND_STATUS_ERROR);
        }
        if ($captchaCode->status === CaptchaStatusEnum::USED) {
            throw new CaptchaException('发送状态错误', CaptchaException::SEND_STATUS_ERROR);
        }
        return true;
    }
}
