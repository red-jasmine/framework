<?php

namespace RedJasmine\UserCore\Domain\Services\ChangeAccount\Providers;

use RedJasmine\Support\Helpers\Services\ServiceManager;

class UserChangeAccountServiceProviderManager extends ServiceManager
{

    protected const  PROVIDERS = [
        PhoneUserChangeAccountServiceProvider::NAME => PhoneUserChangeAccountServiceProvider::class
    ];

}