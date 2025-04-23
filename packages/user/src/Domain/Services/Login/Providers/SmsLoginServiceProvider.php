<?php

namespace RedJasmine\User\Domain\Services\Login\Providers;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaCreateCommand;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaVerifyCommand;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\User\Domain\Exceptions\LoginException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Login\Contracts\UserLoginServiceProviderInterface;
use RedJasmine\User\Domain\Services\Login\Data\UserLoginData;

class SmsLoginServiceProvider implements UserLoginServiceProviderInterface
{
    protected CaptchaApplicationService   $captchaApplicationService;
    protected UserReadRepositoryInterface $userReadRepository;

    public function __construct()
    {

        $this->captchaApplicationService = app(CaptchaApplicationService::class);
        $this->userReadRepository        = app(UserReadRepositoryInterface::class);
    }

    public const string NAME = 'sms';

    /**
     * @param  UserLoginData  $data
     *
     * @return bool
     * @throws LoginException
     */
    public function captcha(UserLoginData $data) : bool
    {
        // 验证用户是否存在
        $mobile = $data->data['mobile'];
        // TODO 验证手机号 格式

        $user = app(UserReadRepositoryInterface::class)->findByMobile($mobile);
        if (!$user) {
            throw new  LoginException('用户未注册');
        }
        if (!$user->isAllowActivity()) {
            throw new  LoginException('用户异常');
        }

        // 发送验证码


        $command = CaptchaCreateCommand::from([
            'type'            => 'login',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['mobile'],
        ]);

        $result = $this->captchaApplicationService->create($command);


        return true;
    }


    public function login(UserLoginData $data) : User
    {

        // 获取账户信息
        $mobile  = $data->data['mobile'];
        $code    = $data->data['code'] ?? null;
        $command = CaptchaVerifyCommand::from([
            'type'            => 'login',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['mobile'],
            'code'            => $code,
        ]);

        $this->captchaApplicationService->verify($command);

        // 查询用户信息
        return app(UserReadRepositoryInterface::class)->findByMobile($mobile);

    }


}
