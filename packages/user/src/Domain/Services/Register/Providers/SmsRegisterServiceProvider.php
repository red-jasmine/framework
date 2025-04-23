<?php

namespace RedJasmine\User\Domain\Services\Register\Providers;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaCreateCommand;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaVerifyCommand;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Exceptions\UserRegisterException;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;
use RedJasmine\User\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\User\Domain\Services\Register\Data\UserRegisterData;

class SmsRegisterServiceProvider implements UserRegisterServiceProviderInterface
{
    protected CaptchaApplicationService   $captchaApplicationService;
    protected UserReadRepositoryInterface $userReadRepository;

    public function __construct()
    {

        $this->captchaApplicationService = app(CaptchaApplicationService::class);
        $this->userReadRepository        = app(UserReadRepositoryInterface::class);
    }

    public const string NAME = 'sms';

    public function captcha(UserRegisterData $data) : UserData
    {

        $this->validate($data);

        $command = CaptchaCreateCommand::from([
            'type'            => 'register',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['mobile'],
        ]);

        $result = $this->captchaApplicationService->create($command);

        return $this->getUserData($data);
    }


    public function getUserData(UserRegisterData $data) : UserData
    {
        $userData = new UserData();

        $userData->name   = $data->data['name'] ?? null;
        $userData->mobile = $data->data['mobile'] ?? null;
        $userData->email  = $data->data['email'] ?? null;

        return $userData;
    }


    /**
     * @param  UserRegisterData  $data
     *
     * @return void
     * @throws UserRegisterException
     */
    protected function validate(UserRegisterData $data) : void
    {

        // 验证手机号是否已经注册

        $mobile = $data->data['mobile'] ?? null;

        $hasUser = $this->userReadRepository->findByMobile($mobile);
        if ($hasUser) {
            throw  new UserRegisterException('手机号已经注册');
        }
    }

    /**
     * @param  UserRegisterData  $data
     *
     * @return UserData
     * @throws UserRegisterException
     */
    public function register(UserRegisterData $data) : UserData
    {
        // 验证验证码 TODO
        $this->validate($data);
        $code    = $data->data['code'] ?? null;
        $command = CaptchaVerifyCommand::from([
            'type'            => 'register',
            'app'             => 'app',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['mobile'],
            'code'            => $code,
        ]);

        $this->captchaApplicationService->verify($command);


        return $this->getUserData($data);

    }


}
