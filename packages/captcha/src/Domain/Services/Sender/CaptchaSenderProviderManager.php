<?php

namespace RedJasmine\Captcha\Domain\Services\Sender;

use RedJasmine\Captcha\Domain\Services\Sender\Contracts\CaptchaSenderInterface;
use RedJasmine\Captcha\Domain\Services\Sender\Providers\SmsCaptchaSenderProvider;
use RedJasmine\Support\Helpers\Services\ServiceManager;

/**
 * @method CaptchaSenderInterface create(string $name)
 */
class CaptchaSenderProviderManager extends ServiceManager
{


    protected const  PROVIDERS = [
        SmsCaptchaSenderProvider::NAME => SmsCaptchaSenderProvider::class,

    ];
}