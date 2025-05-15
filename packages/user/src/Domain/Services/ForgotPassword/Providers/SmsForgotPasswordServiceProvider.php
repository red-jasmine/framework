<?php

namespace RedJasmine\User\Domain\Services\ForgotPassword\Providers;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaCreateCommand;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaVerifyCommand;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\User\Domain\Exceptions\LoginException;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\ForgotPassword\Contracts\UserForgotPasswordServiceProviderInterface;
use RedJasmine\User\Domain\Services\ForgotPassword\Data\ForgotPasswordData;

class SmsForgotPasswordServiceProvider implements UserForgotPasswordServiceProviderInterface
{

    public const string  NAME = 'sms';
    protected CaptchaApplicationService   $captchaApplicationService;
    protected UserReadRepositoryInterface $userReadRepository;

    public function __construct()
    {

        $this->captchaApplicationService = app(CaptchaApplicationService::class);
        $this->userReadRepository        = app(UserReadRepositoryInterface::class);
    }

    public function captcha(ForgotPasswordData $data) : bool
    {
        // 查询账户

        // 发送验证码
        $command = CaptchaCreateCommand::from([
            'type'            => 'forgot-password',
            'app'             => 'app',
            'method'         => 'sms',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'],
        ]);

        $result = $this->captchaApplicationService->create($command);


        return true;
    }

    protected function validate(ForgotPasswordData $data) : User
    {
        $phone = $data->data['phone'];
        // 发送验证码
        $user = app(UserReadRepositoryInterface::class)->findByPhone($phone);
        if (!$user) {
            throw new  LoginException('用户未注册');
        }
        if (!$user->isAllowActivity()) {
            throw new  LoginException('用户异常');
        }

        return $user;

    }

    /**
     * @param  ForgotPasswordData  $data
     *
     * @return int
     * @throws LoginException
     */
    public function verify(ForgotPasswordData $data) : int
    {
        // 验证用户
        $user = $this->validate($data);

        // 验证验证码
        $code = $data->data['code'] ?? null;


        $command = CaptchaVerifyCommand::from([
            'type'            => 'forgot-password',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'],
            'code'            => $code,
        ]);

        $this->captchaApplicationService->verify($command);


        return $user->id;
    }


}