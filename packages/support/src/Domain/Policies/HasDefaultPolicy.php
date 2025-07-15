<?php

namespace RedJasmine\Support\Domain\Policies;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;

trait HasDefaultPolicy
{

    public function before($user, string $ability) : bool|null
    {
        // 超级管理员
        if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            return true;
        }
        return null;
    }

    public static function getModel() : string
    {
        return '';
    }

    protected function defaultPermission(string $function) : string
    {
        return static::getModel().'.'.$function;
    }

    protected function buildPermissions(string $function) : array
    {
        return [
            $this->defaultPermission($function),
            Str::of($function)->snake()->lower().'_'.static::getModel(),
            Str::of($function)->snake('-')->lower().'_'.static::getModel(),
        ];
    }


    public function viewAny($user) : bool
    {
        // 当前登录用户
        return $user->can($this->defaultPermission(__FUNCTION__));
    }

    public function view($user, $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function create($user) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function update($user, $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function delete($user, $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function restore($user, $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function forceDelete($user, $model) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function deleteAny($user) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function forceDeleteAny($user) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }

    public function restoreAny($user) : bool
    {
        return $user->canany($this->buildPermissions(__FUNCTION__));
    }


}