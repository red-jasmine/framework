<?php

namespace RedJasmine\Captcha\Domain\Repositories;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface CaptchaRepositoryInterface extends RepositoryInterface
{


    public function findLastCodeByNotifiable(CaptchaData $captchaData) : ?Captcha;
}