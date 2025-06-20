<?php

namespace RedJasmine\Distribution\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamReadRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamRepositoryInterface;
use RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql\PromoterApplyReadRepository;
use RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql\PromoterGroupReadRepository;
use RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql\PromoterLevelReadRepository;
use RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql\PromoterOrderReadRepository;
use RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql\PromoterReadRepository;
use RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql\PromoterTeamReadRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\Eloquent\PromoterApplyRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\Eloquent\PromoterGroupRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\Eloquent\PromoterLevelRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\Eloquent\PromoterOrderRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\Eloquent\PromoterRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\Eloquent\PromoterTeamRepository;

class DistributionApplicationServiceProvider extends ServiceProvider {

    public function register() : void
    {

        $this->app->bind(PromoterReadRepositoryInterface::class,PromoterReadRepository::class);
        $this->app->bind(PromoterRepositoryInterface::class,PromoterRepository::class);

        $this->app->bind(PromoterGroupReadRepositoryInterface::class,PromoterGroupReadRepository::class);
        $this->app->bind(PromoterGroupRepositoryInterface::class,PromoterGroupRepository::class);

        $this->app->bind(PromoterLevelReadRepositoryInterface::class,PromoterLevelReadRepository::class);
        $this->app->bind(PromoterLevelRepositoryInterface::class,PromoterLevelRepository::class);

        $this->app->bind(PromoterTeamReadRepositoryInterface::class,PromoterTeamReadRepository::class);
        $this->app->bind(PromoterTeamRepositoryInterface::class,PromoterTeamRepository::class);

        $this->app->bind(PromoterOrderReadRepositoryInterface::class,PromoterOrderReadRepository::class);
        $this->app->bind(PromoterOrderRepositoryInterface::class,PromoterOrderRepository::class);

        $this->app->bind(PromoterApplyReadRepositoryInterface::class,PromoterApplyReadRepository::class);
        $this->app->bind(PromoterApplyRepositoryInterface::class,PromoterApplyRepository::class);
    }

    public function boot(){

    }

}
