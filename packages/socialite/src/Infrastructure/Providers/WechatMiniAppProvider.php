<?php

namespace RedJasmine\Socialite\Infrastructure\Providers;

use GuzzleHttp\Utils;
use Overtrue\Socialite\Contracts;
use Overtrue\Socialite\Exceptions;
use Overtrue\Socialite\Providers\WeChat;
use Overtrue\Socialite\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use function is_array;
use function is_string;
use const JSON_UNESCAPED_UNICODE;
use const Overtrue\Socialite\Contracts;

class WechatMiniAppProvider extends WeChat
{
    public const string NAME = 'wechat_mini';

    /**
     * @throws Exceptions\AuthorizeFailedException
     */
    protected function normalizeJsCodeResponses(mixed $response) : array
    {
        if ($response instanceof StreamInterface) {
            $response->tell() && $response->rewind();
            $response = (string)$response;
        }

        if (is_string($response)) {
            $response = Utils::jsonDecode($response, true);
        }

        if (!is_array($response)) {
            throw new Exceptions\AuthorizeFailedException('Invalid token response', [ $response ]);
        }

        if (empty($response['openid'])) {
            throw new Exceptions\AuthorizeFailedException('Authorize Failed: ' . Utils::jsonEncode($response, JSON_UNESCAPED_UNICODE), $response);
        }

        return $response;
    }

    protected function getJsCodeUrl() : string
    {
        return $this->baseUrl . '/jscode2session';
    }


    protected function getJsCodeFields(string $code) : array
    {
        return empty($this->component) ? [
            'appid'      => $this->getClientId(),
            'secret'     => $this->getClientSecret(),
            'js_code'    => $code,
            'grant_type' => 'authorization_code',
        ] : [
            'appid'                           => $this->getClientId(),
            'component_appid'                 => $this->component[Contracts\ABNF_ID],
            'component_access_token'          => $this->component[Contracts\ABNF_TOKEN],
            Contracts\RFC6749_ABNF_CODE       => $code,
            Contracts\RFC6749_ABNF_GRANT_TYPE => Contracts\RFC6749_ABNF_AUTHORATION_CODE,
        ];
    }

    protected function getUserFromJsCode(string $code) : ResponseInterface
    {
        return $this->getHttpClient()->get($this->getJsCodeUrl(), [
            'headers' => [ 'Accept' => 'application/json' ],
            'query'   => $this->getJsCodeFields($code),
        ]);
    }


    /**
     * @param string $code
     * @return Contracts\UserInterface
     * @throws Exceptions\AuthorizeFailedException
     */
    public function userFromCode(string $code) : Contracts\UserInterface
    {

        //  只获取用户 openid 信息
        $response = $this->getUserFromJsCode($code);

        $data = $this->normalizeJsCodeResponses($response->getBody());

        return $this->mapUserToObject($data);

    }


    protected function mapUserToObject(array $user) : Contracts\UserInterface
    {
        $model = new User([
                              Contracts\ABNF_APP_ID   => $this->getClientId(),
                              Contracts\ABNF_ID       => $user['openid'] ?? null,
                              Contracts\ABNF_NAME     => $user[Contracts\ABNF_NICKNAME] ?? null,
                              Contracts\ABNF_NICKNAME => $user[Contracts\ABNF_NICKNAME] ?? null,
                              Contracts\ABNF_AVATAR   => $user['headimgurl'] ?? null,
                              Contracts\ABNF_EMAIL    => null,
                          ]);
        $model->setRaw($user);
        return $model;
    }


}
