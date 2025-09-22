<?php

namespace RedJasmine\Project\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use RedJasmine\Project\Domain\Events\MemberJoined;
use RedJasmine\Project\Domain\Events\MemberLeft;
use RedJasmine\Project\Domain\Events\MemberRoleChanged;
use RedJasmine\Project\Domain\Events\ProjectActivated;
use RedJasmine\Project\Domain\Events\ProjectArchived;
use RedJasmine\Project\Domain\Events\ProjectCreated;
use RedJasmine\Project\Domain\Events\ProjectPaused;
use RedJasmine\Project\Domain\Events\ProjectRoleCreated;
use RedJasmine\Project\Domain\Events\ProjectRoleDeleted;
use RedJasmine\Project\Infrastructure\Listeners\ProjectEventListener;

class ProjectEventServiceProvider extends ServiceProvider
{
    /**
     * 事件监听器映射
     */
    protected $listen = [
        ProjectCreated::class => [
            ProjectEventListener::class . '@handleProjectCreated',
        ],
        ProjectActivated::class => [
            ProjectEventListener::class . '@handleProjectActivated',
        ],
        ProjectPaused::class => [
            ProjectEventListener::class . '@handleProjectPaused',
        ],
        ProjectArchived::class => [
            ProjectEventListener::class . '@handleProjectArchived',
        ],
        MemberJoined::class => [
            ProjectEventListener::class . '@handleMemberJoined',
        ],
        MemberLeft::class => [
            ProjectEventListener::class . '@handleMemberLeft',
        ],
        MemberRoleChanged::class => [
            ProjectEventListener::class . '@handleMemberRoleChanged',
        ],
        ProjectRoleCreated::class => [
            ProjectEventListener::class . '@handleProjectRoleCreated',
        ],
        ProjectRoleDeleted::class => [
            ProjectEventListener::class . '@handleProjectRoleDeleted',
        ],
    ];

    /**
     * 注册服务
     */
    public function register(): void
    {
        //
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        parent::boot();
    }
}
