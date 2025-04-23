<?php

namespace RedJasmine\Captcha\Domain\Services;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Captcha\Exceptions\CaptchaException;
use RedJasmine\Captcha\Jobs\CaptchaSendJob;

class CaptchaVerifyService
{
    public function __construct(
        public CaptchaRepositoryInterface $repository
    ) {
    }


    /**
     * @param  CaptchaData  $captchaData
     * @param  string  $code
     *
     * @return Captcha
     * @throws CaptchaException
     */
    public function verify(CaptchaData $captchaData, string $code) : Captcha
    {
        if (blank($code)) {
            throw new CaptchaException('验证码不能为空', CaptchaException::SEND_ERROR);
        }
        // 查询 有效期内最后一条 统一类型的验证码

        $captcha = $this->repository->findLastCodeByNotifiable($captchaData);

        if (blank($captcha)) {
            throw new CaptchaException('验证码错误', CaptchaException::SEND_ERROR);
        }
        if ($captcha->code !== $code) {
            throw new CaptchaException('验证码错误', CaptchaException::SEND_ERROR);
        }
        // 验证状态
        if ($captcha->status !== CaptchaStatusEnum::WAIT) {
            throw new CaptchaException('验证码已使用', CaptchaException::SEND_ERROR);
        }
        // 验证过期时间
        if (now() > $captcha->exp_time) {
            throw new CaptchaException('验证码已过期,请重新发送', CaptchaException::SEND_ERROR);
        }

        $captcha->setVerified();
        return $captcha;

    }


}
