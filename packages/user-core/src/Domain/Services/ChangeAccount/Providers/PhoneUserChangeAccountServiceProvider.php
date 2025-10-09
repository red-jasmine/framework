<?php

namespace RedJasmine\UserCore\Domain\Services\ChangeAccount\Providers;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaCreateCommand;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaVerifyCommand;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Exceptions\UserRegisterException;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Contracts\UserChannelAccountServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Data\UserChangeAccountData;

class PhoneUserChangeAccountServiceProvider implements UserChannelAccountServiceProviderInterface
{

    protected CaptchaApplicationService   $captchaApplicationService;
    protected BaseUserRepositoryInterface $userRepository;

    public function __construct()
    {

        $this->captchaApplicationService = app(CaptchaApplicationService::class);
        $this->userRepository        = app(BaseUserRepositoryInterface::class);
    }

    public const  NAME = 'phone';

    public function captcha(User $user, UserChangeAccountData $data) : bool
    {
        // 验证新手机号
        $this->validate($data);


        if ($user->phone) {
            // 发送老手机验证码
            $command = CaptchaCreateCommand::from([
                'type'            => 'change-account',
                'app'             => 'app',
                'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
                'notifiable_id'   => $user->phone,
            ]);

            $result = $this->captchaApplicationService->create($command);
        }
        return true;

    }

    public function verify(User $user, UserChangeAccountData $data) : bool
    {
        // 验证新手机号
        $this->validate($data);

        
        if ($user->phone) {
            // 验证原来手机验证码
            $command = CaptchaVerifyCommand::from([
                'type'            => 'change-account',
                'app'             => 'app',
                'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
                'notifiable_id'   => $user->phone,
                'code'            => $data->data['code'] ?? null,
            ]);

            $this->captchaApplicationService->verify($command);
        }


        //  发送新手机验证码
        $command = CaptchaCreateCommand::from([
            'type'            => 'change-account-verify',
            'app'             => 'app',
            'method'         => 'sms',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'] ?? null,
        ]);

        $result = $this->captchaApplicationService->create($command);

        return true;
    }

    public function change(User $user, UserChangeAccountData $data) : bool
    {
        $this->validate($data);
        // 验证原来手机验证码
        $command = CaptchaVerifyCommand::from([
            'type'            => 'change-account-verify',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'] ?? null,
            'code'            => $data->data['code'] ?? null,
        ]);

        $this->captchaApplicationService->verify($command);


        $user->changePhone($data->data['phone']);

        return true;
    }


    protected function validate(UserChangeAccountData $data) : void
    {

        // 验证手机号是否已经注册

        $phone = $data->data['phone'] ?? null;

        $hasUser = $this->userRepository->findByPhone($phone);
        if ($hasUser) {
            throw  new UserRegisterException('手机号已经注册');
        }
    }

}