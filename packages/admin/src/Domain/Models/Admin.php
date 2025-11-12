<?php

namespace RedJasmine\Admin\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Admin\Domain\Events\AdminCancelEvent;
use RedJasmine\Admin\Domain\Events\AdminLoginEvent;
use RedJasmine\Admin\Domain\Events\AdminRegisteredEvent;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\SystemAdmin;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\System;
use RedJasmine\User\Domain\Models\User;
use Spatie\Permission\Traits\HasRoles;

class Admin extends User implements BelongsToOwnerInterface, SystemAdmin
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
        // TODO 跟据角色判断
        return true;
    }


    public function getType() : string
    {
        return 'admin';
    }

    public function getJWTCustomClaims() : array
    {
        return array_merge(parent::getJWTCustomClaims(), [
            'is_system_admin' => $this->isSystemAdmin()
        ]);
    }

    /**
     * 是否是系统管理员
     * @return bool
     */
    public function isSystemAdmin() : bool
    {

        return true;
    }


}
