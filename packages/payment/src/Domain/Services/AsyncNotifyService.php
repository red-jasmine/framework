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
     * @param  array  $body
     * @param $privateKey
     * @param  string  $signType
     *
     * @return string
     * @throws Exception
     */
    protected function sign(array $body, $privateKey, string $signType) : string
    {
        $signer = new Signer($body);
        $signer->setEncodePolicy('JSON');
        $signer->setIgnores(['sign', 'sign_type']);
        if ($signType === 'RSA2') {
            return $signer->signWithRSA($privateKey, OPENSSL_ALGO_SHA256);
        } elseif ($signType === 'RSA') {
            return $signer->signWithRSA($privateKey);
        } else {
            throw new InvalidArgumentException('The sign type is invalid');
        }

    }

    /**
     * @param  Notify  $notify
     *
     * @return void
     * @throws Exception
     */
    public function notify(Notify $notify) : void
    {
        Log::info('AsyncNotifyService.notify', [
            'notify_no' => $notify->notify_no,
            'url'       => $notify->url,
            'body'      => $notify->body,
        ]);

        // 获取参数
        $url  = $notify->url;
        $body = $notify->body;

        // 根据应用获取 TODO
        //$privateKey = 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCpmvFWdd4MiVi4kpzBSW4ApsONtOVEyynLItIQTprVG21DJfz061GVl5+QqNzGy54tmSC0m/4yNjlaMjqIH8OfDuhE8qM5YamEzP5F+nisYQwYzn9JyoUTiLvL6iHPhayopfK/1gGAPi4lnYIra6jUo2tpqWJs7Xmcdipa4kwa6FhG+09QDeCzXTXjGIPNw17XBzu+/qCX4MQIn5CcPaL7n+rR/hURcsXX9xo/b3eWTmT9iyou14aMtvYuyo1CR6oeYyAy31rBDrb7S5/pVOypfEQPRv2j4vfyqvMHtsKSI9cH2cqN7bxNo7f5sQ3fm7ZQcFdRNS3BuWdnkw5US8+3AgMBAAECggEAKW90JIZZQEDRzw3qhAI3gKs9PUKDfKIRzNHt9hPuGcBAmlYOjZtr7BsF3aaTgXG/bC5r4hP2Lzg2HMYGrLjt5s8Sib2piNxGOO6H9HqzvpFbDjhsuo9ioZoXH0NNDVEAFJeuTZWv+i+2wHPRmGAN+B0QodjC9g+yVTo9MMT2iN/xOUF+x3on4pl3PMs4RzIP7ItRIhCORdgqkOGTz5pyW3uJgaDcFFWdn/+GhJ8OHYjJo3v/4DSo7KdzRiSzgAhofmtADe647TQl1ri2yDgDsU3NslHdWRti6vVzoXk/bE4wAEcNZ2Mtv5ahwB3e2R2r0b5hsBkuwQAxpeaQKa3+MQKBgQD5DFZK34N060MDRPj+zADkTCZr898A95IovZzg3Nj8HIWyHiQ5JxE9MZ9oOFN0APnofWj+4k+27tV4Qm0c21C2ea15Th2gru7Llc07CqfzZmE6N55E3VMlCF1l9QTHtcO9HspvxkinOuNYh2hTdJAx3EUZiIFg6vHN5sF6GfXu+QKBgQCuVurrtH66Lf6ioevTcTrHLyEf6IQsSoTd4u+Ndtzn4kkyhzQvhNsNB2jPt44RWz5WuuozrIUbVeyfEzPjq9Stykmvw7oeSoRIJpmhmN707IyXpZ7Yi9ajiMeymbVW2k2Uvxqdr9PxpTRmT0pLscm9Wh6efmtjZ4+cmoJ24lhwLwKBgEsh0KqX0XWlP7stxJxBeQdmfbTVhnIpPduykDA6D+/GvwKkGzNuuMGoRbnQPETjwoDDzLgQGBJM7PNxvq8U7r2N6aqNyVxfScT7NUXZih9gxHYmr0WBK4MhieOnTkDdceaM8m9T9zkUB9/+QZfjs1iHZgU07CsL1dTTB41JZaspAoGAQ6quwc+rxF2n0L3iR45STw9G3xijfIFr8qdrbU/uS/5zhK4fnjYFw5fVoZHQDYKJvqYL0wSqIUMHdXLBmCOQh+fVE+h3K9ymXU7GIIeyOgQ6SX3aGpikNZMYV4T7cnuG0y4diYi+TbNelDtATgLyl5EMA150FgATKKh77OLLircCgYBasbFnbw86qW3vSH8CYoIYpi08TkWRucnnX3gEZCFom1UqbW/0BCzE2ZMR+bepisH0W2i85A1WZ9C6s4WAHGzHNW6xGjm/7r3dPUJgiSxh+mKlwviUjIbhY57qzmcJAW5Yq+KXXC4S28gG0fzuiJxC7DAbVKyNmFAQPMY6xc37uQ==';
        $signType   = 'RSA2';


        $body['notify_type'] = $notify->notify_type;
        $body['notify_time'] = now()->format('Y-m-d hH:i:s');
        $body['sign_type']   = $signType;
        $body['sign']        = $this->sign($body, $privateKey, $signType);

        // 发送请求
        $response = $this->request($url, $body);
        // 设置响应
        $notify->setResponse($response);


    }

    public function request(string $url, array $body) : array
    {
        Log::info('AsyncNotifyService.request', [
            'url'  => $url,
            'body' => $body,
        ]);
        try {
            $client = new Client([
                'http_errors' => false,
                'timeout'     => 5
            ]);

            $response = $client->request('POST', $url, [
                'json' => $body
            ]);

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
