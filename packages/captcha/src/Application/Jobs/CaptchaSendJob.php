<?php

namespace RedJasmine\Captcha\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaSendCommand;
use Throwable;

class CaptchaSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public int $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {

        $this->onQueue('captcha');
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle() : void
    {
        try {
            app(CaptchaApplicationService::class)->send(CaptchaSendCommand::from(['id' => $this->id]));
        } catch (Throwable $exception) {
            report($exception);
        }


    }
}
