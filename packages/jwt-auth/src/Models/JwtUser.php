<?php

namespace RedJasmine\JwtAuth\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Contracts\SystemAdmin;
use RedJasmine\Support\Contracts\UserInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;

class JwtUser extends Authenticatable implements JWTSubject, UserInterface, BelongsToOwnerInterface,SystemAdmin
{


    protected $fillable = [
        'id',
        'type',
        'nickname',
        'name',
        'is_system_admin',
    ];

    public function getJWTIdentifier()
    {
        return $this->getID();
    }

    public function getID() : string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() : array
    {
        return [];
    }

    // Authenticatable 接口实现

    public function owner() : UserInterface
    {
        // 如果存在 owner_id 属性，则返回对应的用户
        if (isset($this->attributes['owner_id'])) {
            $ownerAttributes = [
                'sub'  => $this->attributes['owner_id'],
                'id'   => $this->attributes['owner_id'],
                'type' => $this->attributes['owner_type'],
            ];

            return new JwtUser($ownerAttributes);
        }

        return $this;
    }

    // 设置所有属性

    public function getType() : string
    {
        return $this->type;
    }

    // 合并属性

    public function getNickname() : ?string
    {
        return $this->nickname ?? null;
    }

    // 转换为数组

    public function getAvatar() : ?string
    {
        return $this->avatar ?? null;
    }

    // 转换为JSON

    public function getUserData() : array
    {
        return $this->toArray();
    }

    /**
     * 是否是系统管理员
     * @return bool
     */
    public function isSystemAdmin() : bool
    {
        return  (bool)($this->is_system_admin ?? false);
    }

}
