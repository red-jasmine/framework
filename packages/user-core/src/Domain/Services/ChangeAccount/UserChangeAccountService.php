<?php

namespace RedJasmine\UserCore\Domain\Services\ChangeAccount;

use RedJasmine\UserCore\Domain\Data\UserSetAccountData;
use RedJasmine\UserCore\Domain\Exceptions\UserRegisterException;
use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Contracts\UserChannelAccountServiceProviderInterface;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Data\UserChangeAccountData;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Facades\UserChangeAccountServiceProvider;

class UserChangeAccountService implements UserChannelAccountServiceProviderInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function getProvider(UserChangeAccountData $data) : UserChannelAccountServiceProviderInterface
    {
        return UserChangeAccountServiceProvider::create($data->provider);
    }

    public function captcha(User $user, UserChangeAccountData $data) : bool
    {
        $provider = $this->getProvider($data);

        return $provider->captcha($user, $data);

    }


    public function verify(User $user, UserChangeAccountData $data) : bool
    {
        $provider = $this->getProvider($data);

        return $provider->verify($user, $data);
    }

    public function change(User $user, UserChangeAccountData $data) : bool
    {
        $provider = $this->getProvider($data);

        return $provider->change($user, $data);
    }

    public function setAccount(User $user, UserSetAccountData $data) : void
    {

        if ($data->phone && $user->phone !== $data->phone) {
            // 查询是否已经存在
            $hasUser = $this->userRepository->findByPhone($data->phone);
            if ($hasUser && $user->id !== $hasUser->id) {
                throw  new UserRegisterException('手机号已经存在');
            }

        }

        if ($data->email && $user->email !== $data->email) {
            // 查询是否已经存在
            $hasUser = $this->userRepository->findByEmail($data->email);
            if ($hasUser && $user->id !== $hasUser->id) {
                throw  new UserRegisterException('邮箱已经存在');
            }

        }

        if ($data->name && $user->name !== $data->name) {
            // 查询是否已经存在
            $hasUser = $this->userRepository->findByName($data->name);
            if ($hasUser && $user->id !== $hasUser->id) {
                throw  new UserRegisterException('邮箱已经存在');
            }

        }
        $user->name  = $data->name;
        $user->phone = $data->phone;
        $user->email = $data->email;

    }


}