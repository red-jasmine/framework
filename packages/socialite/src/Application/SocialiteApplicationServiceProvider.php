<?php

namespace RedJasmine\Socialite\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Socialite\Infrastructure\Repositories\SocialiteUserRepository;

/**
 * 社交登录应用服务提供者
 *
 * 使用统一的仓库接口，简化服务绑定
 */
class SocialiteApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 统一仓库接口绑定，支持读写操作
        $this->app->bind(SocialiteUserRepositoryInterface::class, SocialiteUserRepository::class);
    }

    public function boot(): void
    {
    }
}
