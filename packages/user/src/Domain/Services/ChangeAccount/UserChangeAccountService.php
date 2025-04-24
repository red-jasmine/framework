<?php

namespace RedJasmine\User\Domain\Services\ChangeAccount;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\ChangeAccount\Contracts\UserChannelAccountServiceProviderInterface;
use RedJasmine\User\Domain\Services\ChangeAccount\Data\UserChangeAccountData;
use RedJasmine\User\Domain\Services\ChangeAccount\Facades\UserChangeAccountServiceProvider;

class UserChangeAccountService implements UserChannelAccountServiceProviderInterface
{

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


}