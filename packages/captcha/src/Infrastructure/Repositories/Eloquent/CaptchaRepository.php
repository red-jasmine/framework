<?php

namespace RedJasmine\Captcha\Infrastructure\Repositories\Eloquent;

use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CaptchaRepository extends EloquentRepository implements CaptchaRepositoryInterface
{

    protected static string $eloquentModelClass = Captcha::class;

}