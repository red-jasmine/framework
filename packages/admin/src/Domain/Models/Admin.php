<?php

namespace RedJasmine\Admin\Domain\Models;


use RedJasmine\Admin\Domain\Events\AdminCancelEvent;
use RedJasmine\Admin\Domain\Events\AdminLoginEvent;
use RedJasmine\Admin\Domain\Events\AdminRegisteredEvent;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\System;
use RedJasmine\User\Domain\Models\User;
use Spatie\Permission\Traits\HasRoles;

class Admin extends User implements BelongsToOwnerInterface
{


    public static string $tagModelClass   = AdminTag::class;
    public static string $tagTable        = 'admin_tag_pivot';
    public static string $groupModelClass = AdminGroup::class;

    public $uniqueShortId = true;


    use HasRoles;

    protected $dispatchesEvents = [
        'login'    => AdminLoginEvent::class,
        'register' => AdminRegisteredEvent::class,
        'cancel'   => AdminCancelEvent::class,
    ];

    public function owner() : UserInterface
    {

        return System::make();
    }

    // 超级管理员
    public function isAdministrator() : bool
    {
        return true;
    }

    //  平台管理员 TODO


    public function getType() : string
    {
        return 'admin';
    }


}
