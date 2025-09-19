<?php

namespace RedJasmine\Organization\Domain;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Organization\Domain\Repositories\DepartmentManagerRepositoryInterface;
use RedJasmine\Organization\Domain\Repositories\DepartmentRepositoryInterface;
use RedJasmine\Organization\Domain\Repositories\MemberDepartmentRepositoryInterface;
use RedJasmine\Organization\Domain\Repositories\MemberPositionRepositoryInterface;
use RedJasmine\Organization\Domain\Repositories\MemberRepositoryInterface;
use RedJasmine\Organization\Domain\Repositories\OrganizationRepositoryInterface;
use RedJasmine\Organization\Domain\Repositories\PositionRepositoryInterface;
use RedJasmine\Organization\Infrastructure\Repositories\DepartmentManagerRepository;
use RedJasmine\Organization\Infrastructure\Repositories\DepartmentRepository;
use RedJasmine\Organization\Infrastructure\Repositories\MemberDepartmentRepository;
use RedJasmine\Organization\Infrastructure\Repositories\MemberPositionRepository;
use RedJasmine\Organization\Infrastructure\Repositories\MemberRepository;
use RedJasmine\Organization\Infrastructure\Repositories\OrganizationRepository;
use RedJasmine\Organization\Infrastructure\Repositories\PositionRepository;

class OrganizationDomainServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 注册仓库实现
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(PositionRepositoryInterface::class, PositionRepository::class);
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(MemberDepartmentRepositoryInterface::class, MemberDepartmentRepository::class);
        $this->app->bind(MemberPositionRepositoryInterface::class, MemberPositionRepository::class);
        $this->app->bind(DepartmentManagerRepositoryInterface::class, DepartmentManagerRepository::class);
    }

    public function boot() : void
    {
    }
}


