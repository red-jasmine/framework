<?php

namespace RedJasmine\Payment\Domain\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
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
     * @return void
     * @throws Exception
     */
    public function notify(Notify $notify) : void
    {

        Log::withContext([ 'notify_no' => $notify->notify_no ]);
        Log::info('AsyncNotifyService.notify');

        // 获取参数
        $url  = $notify->url;
        $body = $notify->body;

        $merchantApp = $notify->merchantApp;
        $privateKey  = $merchantApp->system_private_key;
        // 固定签名
        $signType            = 'RSA2';
        $body['notify_no']   = $notify->notify_no;
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

        $result['status'] = 0;
        $result['body']   = null;

        try {
            $client = new Client([ 'http_errors' => false, 'timeout' => 3.0, 'allow_redirects' => false ]);

            $response = $client->request('POST', $url, [ 'json' => $body ]);

            $result['status'] = $response->getStatusCode();
            $result['body']   = $response->getBody()->getContents();

        } catch (ConnectException $connectException) {
            $result['status'] = 504;
            $result['body']   = 'request timeout';
        } catch (Throwable $throwable) {
            report($throwable);
            $result['status'] = 500;
            $result['body']   = null;
        }
        Log::info('AsyncNotifyService.response', $result);
        return $result;


    }

}
