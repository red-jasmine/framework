<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\Support\Facades\AES;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Support\Domain\Data\Queries\Query;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 管理员仓库实现
 *
 * 提供管理员数据的读写操作统一实现
 */
class AdminRepository extends Repository implements AdminRepositoryInterface
{
    protected static string $modelClass = Admin::class;

    /**
     * 根据用户名查找管理员
     */
    public function findByName(string $name) : ?Admin
    {
        return $this->query()->where('name', $name)->first();
    }

    /**
     * 根据邮箱查找管理员
     */
    public function findByEmail(string $email) : ?Admin
    {
        return $this->query()->where('email', AES::encryptString($email))->first();
    }

    /**
     * 根据手机号查找管理员
     */
    public function findByPhone(string $phone) : ?Admin
    {
        return $this->query()->where('phone', AES::encryptString($phone))->first();
    }

    /**
     * 根据登录账号信息查找管理员
     */
    public function findByAccount(string $account) : ?Admin
    {
        return $this->query()
                    ->where('name', $account)
                    ->orWhere('email', AES::encryptString($account))
                    ->orWhere('phone', AES::encryptString($account))
                    ->first();
    }

    /**
     * 根据认证凭据查找管理员
     */
    public function findByConditions($credentials) : ?Admin
    {
        return $this->query()->where($credentials)->first();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters(?Query $query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('group_id'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('nickname'),
            AllowedFilter::partial('email'),
            AllowedFilter::partial('phone'),
        ];
    }

    /**
     * 配置允许的排序字段
     *
     * @param  Query|null  $query
     *
     * @return array
     */
    protected function allowedSorts(?Query $query = null) : array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('name'),
            AllowedSort::field('nickname'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('last_active_at'),
        ];
    }

    /**
     * 配置允许的包含关联
     */
    protected function allowedIncludes(?Query $query = null) : array
    {
        return [
            'group',
            'tags',
            'roles',
            'permissions',
        ];
    }
}