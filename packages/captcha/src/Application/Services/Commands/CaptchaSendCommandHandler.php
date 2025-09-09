<?php

namespace RedJasmine\Captcha\Application\Services\Commands;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Domain\Services\CaptchaSenderService;
use RedJasmine\Captcha\Exceptions\CaptchaException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use Throwable;

class CaptchaSendCommandHandler extends CommandHandler
{


    public function __construct(
        public CaptchaApplicationService $service,
        public CaptchaSenderService $senderService,
    ) {
        $this->initHandleContext();
    }

    /**
     * @param  CaptchaSendCommand  $command
     *
     * @return void
     * @throws Throwable
     * @throws CaptchaException
     */
    public function handle(CaptchaSendCommand $command) : void
    {

        $this->context->setCommand($command);

        $model = $this->service->repository->find($command->getKey());

        $this->context->setModel($model);

        $this->beginDatabaseTransaction();

        try {
            $this->service->hook('send.validate', $this->context, function () {
            });

            $this->senderService->send($this->context->getModel());

            $this->service->repository->update($this->context->getModel());
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }

}
