<?php

namespace RedJasmine\Project\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use RedJasmine\Project\Domain\Events\MemberJoined;
use RedJasmine\Project\Domain\Events\MemberLeft;
use RedJasmine\Project\Domain\Events\MemberRoleChanged;
use RedJasmine\Project\Domain\Events\ProjectActivated;
use RedJasmine\Project\Domain\Events\ProjectArchived;
use RedJasmine\Project\Domain\Events\ProjectCreated;
use RedJasmine\Project\Domain\Events\ProjectPaused;
use RedJasmine\Project\Domain\Events\ProjectRoleCreated;
use RedJasmine\Project\Domain\Events\ProjectRoleDeleted;

class ProjectEventListener
{
    /**
     * 处理项目创建事件
     */
    public function handleProjectCreated(ProjectCreated $event): void
    {
        Log::info('项目已创建', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'creator_id' => $event->creator->getID(),
            'creator_type' => $event->creator->getType(),
        ]);
    }

    /**
     * 处理项目激活事件
     */
    public function handleProjectActivated(ProjectActivated $event): void
    {
        Log::info('项目已激活', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'operator_id' => $event->operator->getID(),
            'operator_type' => $event->operator->getType(),
        ]);
    }

    /**
     * 处理项目暂停事件
     */
    public function handleProjectPaused(ProjectPaused $event): void
    {
        Log::info('项目已暂停', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'operator_id' => $event->operator->getID(),
            'operator_type' => $event->operator->getType(),
        ]);
    }

    /**
     * 处理项目归档事件
     */
    public function handleProjectArchived(ProjectArchived $event): void
    {
        Log::info('项目已归档', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'operator_id' => $event->operator->getID(),
            'operator_type' => $event->operator->getType(),
        ]);
    }

    /**
     * 处理成员加入事件
     */
    public function handleMemberJoined(MemberJoined $event): void
    {
        Log::info('成员已加入项目', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'member_id' => $event->member->member_id,
            'member_type' => $event->member->member_type,
            'inviter_id' => $event->inviter?->getID(),
            'inviter_type' => $event->inviter?->getType(),
        ]);
    }

    /**
     * 处理成员离开事件
     */
    public function handleMemberLeft(MemberLeft $event): void
    {
        Log::info('成员已离开项目', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'member_id' => $event->member->member_id,
            'member_type' => $event->member->member_type,
            'operator_id' => $event->operator?->getID(),
            'operator_type' => $event->operator?->getType(),
        ]);
    }

    /**
     * 处理成员角色变更事件
     */
    public function handleMemberRoleChanged(MemberRoleChanged $event): void
    {
        Log::info('成员角色已变更', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'member_id' => $event->member->member_id,
            'member_type' => $event->member->member_type,
            'old_role_id' => $event->oldRole?->id,
            'new_role_id' => $event->newRole->id,
            'operator_id' => $event->operator->getID(),
            'operator_type' => $event->operator->getType(),
        ]);
    }

    /**
     * 处理角色创建事件
     */
    public function handleProjectRoleCreated(ProjectRoleCreated $event): void
    {
        Log::info('项目角色已创建', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'role_id' => $event->role->id,
            'role_name' => $event->role->name,
            'creator_id' => $event->creator->getID(),
            'creator_type' => $event->creator->getType(),
        ]);
    }

    /**
     * 处理角色删除事件
     */
    public function handleProjectRoleDeleted(ProjectRoleDeleted $event): void
    {
        Log::info('项目角色已删除', [
            'project_id' => $event->project->id,
            'project_name' => $event->project->name,
            'role_id' => $event->role->id,
            'role_name' => $event->role->name,
            'operator_id' => $event->operator->getID(),
            'operator_type' => $event->operator->getType(),
        ]);
    }
}
