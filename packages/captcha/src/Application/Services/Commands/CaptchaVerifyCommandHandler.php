<?php

namespace RedJasmine\Captcha\Application\Services\Commands;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Domain\Services\CaptchaSenderService;
use RedJasmine\Captcha\Domain\Services\CaptchaVerifyService;
use RedJasmine\Captcha\Exceptions\CaptchaException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use Throwable;

class CaptchaVerifyCommandHandler extends CommandHandler
{


    public function __construct(
        public CaptchaApplicationService $service,
        public CaptchaSenderService $senderService,
        public CaptchaVerifyService $verifyService,
    ) {
        $this->context = new HandleContext();
    }

    /**
     * @param  CaptchaVerifyCommand  $command
     *
     * @return bool
     * @throws Throwable
     * @throws CaptchaException
     */
    public function handle(CaptchaVerifyCommand $command) : bool
    {

        $this->context->setCommand($command);


        $this->beginDatabaseTransaction();

        try {
            $this->service->hook('verify.validate', $this->context, function () {
            });

            $model = $this->verifyService->verify($this->context->getCommand(), $this->context->getCommand()->code);

            $this->context->setModel($model);

            $this->service->repository->update($this->context->getModel());
            $this->commitDatabaseTransaction();

            return true;
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }
        return false;

    }

}