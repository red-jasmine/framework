<?php

namespace RedJasmine\Promotion;

use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Promotion\Domain\Repositories\ActivityReadRepositoryInterface;
use RedJasmine\Promotion\Domain\Repositories\ActivityRepositoryInterface;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlerFactory;
use RedJasmine\Promotion\Infrastructure\ReadRepositories\Mysql\ActivityReadRepository;
use RedJasmine\Promotion\Infrastructure\Repositories\Eloquent\ActivityRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PromotionPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-promotion')
            ->hasConfigFile()
            ->hasMigrations([
                '2024_01_01_000001_create_promotion_activities_table',
                '2024_01_01_000002_create_promotion_activity_products_table',
                '2024_01_01_000003_create_promotion_activity_skus_table',
                '2024_01_01_000004_create_promotion_activity_orders_table',
            ])
            ->runsMigrations();
    }
    
    public function packageRegistered(): void
    {
        // 注册仓库绑定
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(ActivityReadRepositoryInterface::class, ActivityReadRepository::class);
        
        // 注册应用服务
        $this->app->singleton(ActivityApplicationService::class);
        
        // 注册活动类型处理器工厂
        $this->registerActivityTypeHandlerFactory();
    }
    
    public function packageBooted(): void
    {
        // 注册活动类型处理器
        $this->registerActivityTypeHandlers();
    }
    
    /**
     * 注册活动类型处理器工厂
     */
    protected function registerActivityTypeHandlerFactory(): void
    {
        $this->app->singleton(ActivityTypeHandlerFactory::class, function ($app) {
            $config = $app['config']->get('promotion.activity_handlers', []);
            return ActivityTypeHandlerFactory::getInstance($config);
        });
    }
    
    /**
     * 注册活动类型处理器
     */
    protected function registerActivityTypeHandlers(): void
    {
        // 从配置文件读取自定义处理器
        $customHandlers = config('promotion.custom_activity_handlers', []);
        
        if (!empty($customHandlers)) {
            ActivityTypeHandlerFactory::registerMany($customHandlers);
        }
        
        // 示例：注册自定义活动类型
        // ActivityTypeHandlerFactory::register('lottery', function ($config) {
        //     return new LotteryActivityHandler($config);
        // });
        
        // 或者注册类名
        // ActivityTypeHandlerFactory::register('points_exchange', PointsExchangeActivityHandler::class);
    }
}
