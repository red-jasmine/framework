<?php

namespace RedJasmine\UserCore\Domain\Services\Register\Providers;

use RedJasmine\Captcha\Application\Services\CaptchaApplicationService;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaCreateCommand;
use RedJasmine\Captcha\Application\Services\Commands\CaptchaVerifyCommand;
use RedJasmine\Captcha\Domain\Models\Enums\NotifiableTypeEnum;
use RedJasmine\UserCore\Domain\Data\UserData;
use RedJasmine\UserCore\Domain\Exceptions\UserRegisterException;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\Register\Contracts\UserRegisterServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\Register\Data\UserRegisterData;

class SmsRegisterServiceProvider implements UserRegisterServiceProviderInterface
{
    protected CaptchaApplicationService $captchaApplicationService;


    public function __construct()
    {

        $this->captchaApplicationService = app(CaptchaApplicationService::class);

    }

    protected BaseUserRepositoryInterface $repository;
    protected string                      $guard;

    public function init(BaseUserRepositoryInterface $repository, string $guard) : static
    {
        $this->repository = $repository;

        $this->guard = $guard;

        return $this;
    }


    public const  NAME = 'sms';

    public function captcha(UserRegisterData $data) : UserData
    {

        $this->validate($data);

        $command = CaptchaCreateCommand::from([
            'type'            => 'register',
            'app'             => 'app',
            'method'          => 'sms',
            'notifiable_type' => NotifiableTypeEnum::MOBILE->value,
            'notifiable_id'   => $data->data['phone'],
        ]);

        $result = $this->captchaApplicationService->create($command);

        return $this->getUserData($data);
    }


    public function getUserData(UserRegisterData $data) : UserData
    {
        $userData = new UserData();

        $userData->name           = $data->data['name'] ?? null;
        $userData->phone          = $data->data['phone'] ?? null;
        $userData->email          = $data->data['email'] ?? null;
        $userData->invitationCode = $data->data['invitation_code'] ?? null;

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

        $phone = $data->data['phone'] ?? null;

        $hasUser = $this->repository->findByPhone($phone);
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
            'notifiable_id'   => $data->data['phone'],
            'code'            => $code,
        ]);

        $this->captchaApplicationService->verify($command);


        return $this->getUserData($data);

    }


}
