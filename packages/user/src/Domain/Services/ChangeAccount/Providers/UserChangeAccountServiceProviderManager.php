<?php

namespace RedJasmine\User\Domain\Services\ChangeAccount\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;

class UserChangeAccountServiceProviderManager extends ServiceManager
{

    protected const array PROVIDERS = [
        PhoneUserChangeAccountServiceProvider::NAME => PhoneUserChangeAccountServiceProvider::class
    ];

}