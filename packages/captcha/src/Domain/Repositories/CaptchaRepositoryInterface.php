<?php

namespace RedJasmine\Captcha\Domain\Repositories;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Support\Domain\Repositories\BaseRepositoryInterface;

/**
 * 验证码仓库接口
 *
 * 提供验证码实体的读写操作统一接口
 */
interface CaptchaRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 根据通知对象查找最后一个验证码
     */
    public function findLastCodeByNotifiable(CaptchaData $captchaData) : ?Captcha;
}
