<?php

namespace RedJasmine\Announcement\Application;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Announcement\Infrastructure\Repositories\AnnouncementRepository;
use RedJasmine\Announcement\Infrastructure\Repositories\CategoryRepository;

class AnnouncementApplicationServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        // 注册仓库绑定
        $this->app->bind(AnnouncementRepositoryInterface::class, AnnouncementRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        

    }

    public function boot() : void
    {
        //
    }
}
