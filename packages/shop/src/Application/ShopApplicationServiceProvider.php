<?php

namespace RedJasmine\Shop\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Shop\Application\Services\ShopApplicationService;
use RedJasmine\Shop\Application\Services\ShopGroupApplicationService;
use RedJasmine\Shop\Application\Services\ShopTagApplicationService;
use RedJasmine\Shop\Domain\Repositories\ShopGroupReadRepositoryInterface;
use RedJasmine\Shop\Domain\Repositories\ShopGroupRepositoryInterface;
use RedJasmine\Shop\Domain\Repositories\ShopReadRepositoryInterface;
use RedJasmine\Shop\Domain\Repositories\ShopRepositoryInterface;
use RedJasmine\Shop\Domain\Repositories\ShopTagReadRepositoryInterface;
use RedJasmine\Shop\Domain\Repositories\ShopTagRepositoryInterface;
use RedJasmine\Shop\Domain\Transformers\ShopTransformer;
use RedJasmine\Shop\Infrastructure\ReadRepositories\Mysql\ShopGroupReadRepository;
use RedJasmine\Shop\Infrastructure\ReadRepositories\Mysql\ShopReadRepository;
use RedJasmine\Shop\Infrastructure\ReadRepositories\Mysql\ShopTagReadRepository;
use RedJasmine\Shop\Infrastructure\Repositories\ShopGroupRepository;
use RedJasmine\Shop\Infrastructure\Repositories\ShopRepository;
use RedJasmine\Shop\Infrastructure\Repositories\ShopTagRepository;
use RedJasmine\User\Domain\Transformers\UserTransformer;

class ShopApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 仓库绑定
        $this->app->bind(ShopRepositoryInterface::class, ShopRepository::class);
        $this->app->bind(ShopReadRepositoryInterface::class, ShopReadRepository::class);
        $this->app->bind(ShopGroupRepositoryInterface::class, ShopGroupRepository::class);
        $this->app->bind(ShopGroupReadRepositoryInterface::class, ShopGroupReadRepository::class);
        $this->app->bind(ShopTagRepositoryInterface::class, ShopTagRepository::class);
        $this->app->bind(ShopTagReadRepositoryInterface::class, ShopTagReadRepository::class);

        // 转换器绑定
        $this->app->bind(ShopTransformer::class, ShopTransformer::class);

        // 应用服务绑定
        $this->app->bind(ShopApplicationService::class, ShopApplicationService::class);
        $this->app->bind(ShopGroupApplicationService::class, ShopGroupApplicationService::class);
        $this->app->bind(ShopTagApplicationService::class, ShopTagApplicationService::class);
    }
} 