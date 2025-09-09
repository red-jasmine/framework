<?php

namespace RedJasmine\Distribution\Application;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RedJasmine\Distribution\Application\PromoterBindUser\Listeners\UserRegisteredListener;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterLevelRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Distribution\Domain\Repositories\PromoterTeamRepositoryInterface;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterApplyRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterBindUserRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterGroupRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterLevelRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterOrderRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterRepository;
use RedJasmine\Distribution\Infrastructure\Repositories\PromoterTeamRepository;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;

class DistributionApplicationServiceProvider extends ServiceProvider
{

    public function register() : void
    {

        $this->app->bind(PromoterRepositoryInterface::class, PromoterRepository::class);

        $this->app->bind(PromoterGroupRepositoryInterface::class, PromoterGroupRepository::class);

        $this->app->bind(PromoterLevelRepositoryInterface::class, PromoterLevelRepository::class);

        $this->app->bind(PromoterTeamRepositoryInterface::class, PromoterTeamRepository::class);

        $this->app->bind(PromoterOrderRepositoryInterface::class, PromoterOrderRepository::class);

        $this->app->bind(PromoterApplyRepositoryInterface::class, PromoterApplyRepository::class);

        $this->app->bind(PromoterBindUserRepositoryInterface::class, PromoterBindUserRepository::class);
    }

    public function boot() : void
    {

        Event::listen(UserRegisteredEvent::class, UserRegisteredListener::class);

    }

}
