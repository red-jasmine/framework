<?php

namespace RedJasmine\User\Domain\Services\ChangeAccount\Contracts;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Services\ChangeAccount\Data\UserChangeAccountData;

interface UserChannelAccountServiceProviderInterface
{
    public function captcha(User $user, UserChangeAccountData $data) : bool;

    public function verify(User $user, UserChangeAccountData $data) : bool;

    public function change(User $user, UserChangeAccountData $data) : bool;
}