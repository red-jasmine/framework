<?php

namespace RedJasmine\Payment\Domain\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Support\Helpers\Signer\Signer;
use Throwable;

class AsyncNotifyService
{


    /**
     * @param array $body
     * @param $privateKey
     * @param string $signType
     *
     * @return string
     * @throws Exception
     */
    protected function sign(array $body, $privateKey, string $signType) : string
    {
        $signer = new Signer($body);
        $signer->setEncodePolicy('JSON');
        $signer->setIgnores([ 'sign', 'sign_type' ]);
        if ($signType === 'RSA2') {
            return $signer->signWithRSA($privateKey, OPENSSL_ALGO_SHA256);
        } elseif ($signType === 'RSA') {
            return $signer->signWithRSA($privateKey);
        } else {
            throw new InvalidArgumentException('The sign type is invalid');
        }

    }

    /**
     * @param Notify $notify
     *
     * @return void
     * @throws Exception
     */
    public function notify(Notify $notify) : void
    {
        Log::info('AsyncNotifyService.notify',
                  [ 'notify_no' => $notify->notify_no, 'url' => $notify->url, 'body' => $notify->body, ]
        );

        // 获取参数
        $url  = $notify->url;
        $body = $notify->body;

        $merchantApp = $notify->merchantApp;
        $privateKey  = $merchantApp->system_private_key;

        $signType = 'RSA2';

        $body['notify_type'] = $notify->notify_type;
        $body['notify_time'] = now()->format('Y-m-d hH:i:s');
        $body['sign_type']   = $signType;
        $body['sign']        = $this->sign($body, $privateKey, $signType);

        // 发送请求
        $response = $this->request($url, $body);
        // 设置响应
        $notify->setResponse($response);


    }

    /**
     * @param string $url
     * @param array $body
     * @return array
     */
    public function request(string $url, array $body) : array
    {
        Log::info('AsyncNotifyService.request', [ 'url' => $url, 'body' => $body, ]);
        try {
            $client = new Client([ 'http_errors' => false, 'timeout' => 5 ]);

            $response = $client->request('POST', $url, [ 'json' => $body ]);

            $responseData = [
                'status' => $response->getStatusCode(),
                'body'   => $response->getBody()->getContents(),
            ];

            Log::info('AsyncNotifyService.response', $responseData);

            return $responseData;

        } catch (Throwable $throwable) {
            report($throwable);

        }

        return [];


    }

}
