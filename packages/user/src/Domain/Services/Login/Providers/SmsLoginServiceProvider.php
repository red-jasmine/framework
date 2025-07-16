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
    protected UserReadRepositoryInterface $readRepository;
    protected string                      $guard;

    public function init(UserReadRepositoryInterface $readRepository, string $guard) : static
    {
        $this->readRepository = $readRepository;

        $this->guard = $guard;

        return $this;
    }

    public function __construct()
    {
        $this->captchaApplicationService = app(CaptchaApplicationService::class);
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
        $phone = $data->data['phone'];
        // TODO 验证手机号 格式

        $user = $this->readRepository->findByPhone($phone);
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
            'method'          => 'sms',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'],
        ]);

        $result = $this->captchaApplicationService->create($command);


        return true;
    }


    public function login(UserLoginData $data) : User
    {

        // 获取账户信息
        $phone = $data->data['phone'];
        $code  = $data->data['code'] ?? null;


        $command = CaptchaVerifyCommand::from([
            'type'            => 'login',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'],
            'code'            => $code,
        ]);

        $this->captchaApplicationService->verify($command);

        // 查询用户信息
        return $this->readRepository->findByPhone($phone);

    }


}
