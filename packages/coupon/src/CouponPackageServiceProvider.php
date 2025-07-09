<?php

namespace RedJasmine\Coupon;

use RedJasmine\Coupon\Domain\Repositories\CouponReadRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageReadRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\UserCouponReadRepositoryInterface;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql\CouponReadRepository;
use RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql\CouponUsageReadRepository;
use RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql\UserCouponReadRepository;
use RedJasmine\Coupon\Infrastructure\Repositories\Eloquent\CouponRepository;
use RedJasmine\Coupon\Infrastructure\Repositories\Eloquent\CouponUsageRepository;
use RedJasmine\Coupon\Infrastructure\Repositories\Eloquent\UserCouponRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CouponPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('red-jasmine-coupon')
            ->hasConfigFile()
            ->hasRoutes(['api'])
            ->hasTranslations()
            ->hasMigrations([
                '2024_01_01_000001_create_coupons_table',
                '2024_01_01_000002_create_user_coupons_table',
                '2024_01_01_000003_create_coupon_usages_table',
                '2024_01_01_000004_create_coupon_issue_stats_table',
            ])
            ->runsMigrations();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        // 注册仓库接口绑定
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(CouponReadRepositoryInterface::class, CouponReadRepository::class);
        
        $this->app->bind(UserCouponRepositoryInterface::class, UserCouponRepository::class);
        $this->app->bind(UserCouponReadRepositoryInterface::class, UserCouponReadRepository::class);
        
        $this->app->bind(CouponUsageRepositoryInterface::class, CouponUsageRepository::class);
        $this->app->bind(CouponUsageReadRepositoryInterface::class, CouponUsageReadRepository::class);
    }
}