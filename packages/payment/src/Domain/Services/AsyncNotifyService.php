<?php

namespace RedJasmine\Payment\Domain\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RedJasmine\Payment\Domain\Data\NotifyResponse;
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
        $notify->response($response);


    }


    /**
     * 发起异步通知请求
     *
     * 本函数通过HTTP POST方法向指定URL发送通知数据，并返回通知响应对象
     * 它主要用于在系统间传递消息或触发事件
     *
     * @param string $url 接收通知的URL地址
     * @param array $body 通知的主体内容，为键值对数组
     * @return NotifyResponse 返回通知响应对象，包含状态码和响应体
     */
    public function request(string $url, array $body) : NotifyResponse
    {
        // 记录请求日志，包括请求URL和请求体
        Log::info('AsyncNotifyService.request', [ 'url' => $url, 'body' => $body, ]);

        // 初始化通知响应对象
        $notifyResponse = new NotifyResponse();

        try {
            // 创建HTTP客户端，配置忽略HTTP错误、设置超时时间和禁止重定向
            $client = new Client([ 'http_errors' => false, 'timeout' => 3.0, 'allow_redirects' => false ]);

            // 发起HTTP POST请求，并传递请求体
            $response = $client->request('POST', $url, [ 'json' => $body ]);

            // 获取并设置响应状态码和响应体
            $notifyResponse->statusCode = $response->getStatusCode();
            $notifyResponse->body       = $response->getBody()->getContents();

        } catch (ConnectException $connectException) {
            // 捕获连接异常，通常为请求超时
            // 设置状态码为504，表示网关超时
            $notifyResponse->statusCode = 504;
            $notifyResponse->body       = 'request timeout';

        } catch (Throwable $throwable) {
            // 捕获其他所有异常
            // 报告异常信息，并设置状态码为500，表示服务器内部错误
            report($throwable);
            $notifyResponse->statusCode = 500;
        }

        // 记录响应日志，包括响应状态码和响应体
        Log::info('AsyncNotifyService.response', $notifyResponse->toArray());

        // 返回通知响应对象
        return $notifyResponse;
    }

}
