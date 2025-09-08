<?php

namespace RedJasmine\Captcha\Infrastructure\Repositories\Eloquent;

use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Support\Facades\AES;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CaptchaRepository extends Repository implements CaptchaRepositoryInterface
{

    protected static string $modelClass = Captcha::class;

    public function findLastCodeByNotifiable(CaptchaData $captchaData) : ?Captcha
    {
        return static::$eloquentModelClass::query()
                                          ->where('notifiable_type', $captchaData->notifiableType)
                                          ->where('notifiable_id', AES::encryptString($captchaData->notifiableId))
                                          ->where('type', $captchaData->type)
                                          ->where('app', $captchaData->app)
                                          ->orderByDesc('id')
                                          ->first();
    }


}