<?php

namespace RedJasmine\Captcha\Application\Services;

use RedJasmine\Captcha\Application\Services\Commands\CaptchaSendCommandHandler;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Repositories\CaptchaReadRepositoryInterface;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Captcha\Domain\Transformer\CaptchaTransformer;
use RedJasmine\Support\Application\ApplicationService;

class CaptchaApplicationService extends ApplicationService
{

    protected static string $modelClass = Captcha::class;

    public function __construct(
        public CaptchaRepositoryInterface $repository,
        public CaptchaReadRepositoryInterface $readRepository,
        public CaptchaTransformer $transformer,
    ) {

    }


    protected static $macros = [
        'update' => null,
        'delete' => null,
        'send'   => CaptchaSendCommandHandler::class,
    ];
}