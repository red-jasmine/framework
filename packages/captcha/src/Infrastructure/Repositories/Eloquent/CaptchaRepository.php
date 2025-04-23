<?php

namespace RedJasmine\Captcha\Infrastructure\Repositories\Eloquent;

use Illuminate\Support\Carbon;
use RedJasmine\Captcha\Domain\Data\CaptchaData;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaStatusEnum;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CaptchaRepository extends EloquentRepository implements CaptchaRepositoryInterface
{

    protected static string $eloquentModelClass = Captcha::class;

    public function findLastCodeByNotifiable(CaptchaData $captchaData) : ?Captcha
    {
        return static::$eloquentModelClass::query()
                                          ->where('notifiable_type', $captchaData->notifiableType)
                                          ->where('notifiable_id', $captchaData->notifiableId)
                                          ->where('type', $captchaData->type)
                                          ->where('app', $captchaData->app)
                                          //->where('status', CaptchaStatusEnum::WAIT)
                                          //->where('exp_time', '>', Carbon::now())
                                          ->orderByDesc('id')
                                          ->first();
    }


}