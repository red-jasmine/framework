<?php

namespace RedJasmine\Captcha\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Captcha\Application\Listener\CaptchaSenderListener;
use RedJasmine\Captcha\Domain\Events\CaptchaCreatedEvent;
use RedJasmine\Captcha\Domain\Repositories\CaptchaReadRepositoryInterface;
use RedJasmine\Captcha\Domain\Repositories\CaptchaRepositoryInterface;
use RedJasmine\Captcha\Infrastructure\ReadRepositories\Mysql\CaptchaReadRepository;
use RedJasmine\Captcha\Infrastructure\Repositories\Eloquent\CaptchaRepository;

class CaptchaApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {

        $this->app->bind(CaptchaRepositoryInterface::class, CaptchaRepository::class);
        $this->app->bind(CaptchaReadRepositoryInterface::class, CaptchaReadRepository::class);

    }

    public function boot() : void
    {

        Event::listen(CaptchaCreatedEvent::class, CaptchaSenderListener::class);
    }
}
