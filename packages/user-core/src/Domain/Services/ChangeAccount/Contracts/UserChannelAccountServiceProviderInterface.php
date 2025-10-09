<?php

namespace RedJasmine\UserCore\Domain\Services\ChangeAccount\Contracts;

use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Services\ChangeAccount\Data\UserChangeAccountData;

interface UserChannelAccountServiceProviderInterface
{
    public function captcha(User $user, UserChangeAccountData $data) : bool;

    public function verify(User $user, UserChangeAccountData $data) : bool;

    public function change(User $user, UserChangeAccountData $data) : bool;
}