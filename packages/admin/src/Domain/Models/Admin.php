<?php

namespace RedJasmine\Admin\Domain\Models;


use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\User\Domain\Models\User;
use Spatie\Permission\Traits\HasRoles;

class Admin extends User implements BelongsToOwnerInterface
{


    public static string $tagModelClass   = AdminTag::class;
    public static string $tagTable        = 'admin_tag_pivot';
    public static string $groupModelClass = AdminGroup::class;

    public $uniqueShortId = true;


    use HasRoles;


    public function owner() : UserInterface
    {
        return UserData::from([
            'type' => 'system',
            'id'   => 'system',
        ]);
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
