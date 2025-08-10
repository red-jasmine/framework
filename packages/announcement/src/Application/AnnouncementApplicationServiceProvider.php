<?php

namespace RedJasmine\Announcement\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementReadRepositoryInterface;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Announcement\Domain\Repositories\CategoryReadRepositoryInterface;
use RedJasmine\Announcement\Infrastructure\Repositories\Eloquent\AnnouncementRepository;
use RedJasmine\Announcement\Infrastructure\ReadRepositories\Mysql\AnnouncementReadRepository;
use RedJasmine\Announcement\Infrastructure\Repositories\Eloquent\CategoryRepository;
use RedJasmine\Announcement\Infrastructure\ReadRepositories\Mysql\CategoryReadRepository;

class AnnouncementApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 注册仓库绑定
        $this->app->bind(AnnouncementRepositoryInterface::class, AnnouncementRepository::class);
        $this->app->bind(AnnouncementReadRepositoryInterface::class, AnnouncementReadRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryReadRepositoryInterface::class, CategoryReadRepository::class);
        

    }

    public function boot() : void
    {
        //
    }
}
