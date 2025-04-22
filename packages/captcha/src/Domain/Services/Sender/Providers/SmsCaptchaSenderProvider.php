<?php

namespace RedJasmine\Captcha\Domain\Services\Sender\Providers;

use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use RedJasmine\Captcha\Domain\Models\Captcha;
use RedJasmine\Captcha\Domain\Models\Enums\CaptchaSendStatusEnum;
use RedJasmine\Captcha\Domain\Services\Sender\Contracts\CaptchaSenderInterface;
use RedJasmine\Captcha\Domain\Services\Sender\Contracts\CaptchaSenderResult;
use Throwable;

class SmsCaptchaSenderProvider implements CaptchaSenderInterface
{

    public const string  NAME = 'sms';

    public function send(Captcha $captcha) : CaptchaSenderResult
    {
        try {
            $results = app('easy-sms')->send($captcha->notifiable_id, [
                'content'  => '您的验证码为：${code}，请勿泄露于他人！',
                'template' => 'SMS_276355183',
                'data'     => [
                    'code' => $captcha->code
                ],
            ]);
            Log::info('easy-sms:'.$captcha->notifiable_id.';'.json_encode($results));


            foreach ($results as $channel => $result) {
                if ($result['status'] === 'success') {
                    return CaptchaSenderResult::from([
                        'sendStatus' => CaptchaSendStatusEnum::SEND,
                        'channel'    => $channel,
                        'channelNo'  => $result['result']['BizId'] ?? '',
                    ]);
                }

            }

        } catch (NoGatewayAvailableException $exception) {
            report($exception);
            foreach ($exception->getResults() as $channel => $result) {
                return CaptchaSenderResult::from([
                    'channel'        => $channel,
                    'sendStatus'     => CaptchaSendStatusEnum::FAIL,
                    'channelMessage' => $result['exception']?->getMessage(),
                ]);
            }
            return CaptchaSenderResult::from([
                'sendStatus'     => CaptchaSendStatusEnum::FAIL,
                'channelMessage' => $exception->getMessage()
            ]);

        } catch (Throwable $throwable) {
            report($throwable);
            return CaptchaSenderResult::from([
                'sendStatus'     => CaptchaSendStatusEnum::FAIL,
                'channelMessage' => '系统异常',
            ]);
        }
    }


}