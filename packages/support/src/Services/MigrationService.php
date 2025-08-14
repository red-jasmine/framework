<?php

namespace RedJasmine\Support\Services;

use Illuminate\Database\Schema\Blueprint;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

class MigrationService
{
    public static function register() : void
    {


        Blueprint::macro('userMorphs', function (
            string $name = 'user',
            string $comment = '用户',
            bool $nullable = true,
            bool $hasNickname = true
        ) {
            $this->string($name.'_type', 32)->nullable($nullable)->comment($comment.'类型');
            $this->string($name.'_id', 64)->nullable($nullable)->comment($comment.'ID');
            if ($hasNickname) {
                $this->string($name.'_nickname', 64)->nullable()->comment($comment.'昵称');
            }

        });

        Blueprint::macro('operator', function () {
            $this->unsignedBigInteger('version')->default(0)->comment('版本');
            $this->userMorphs('creator', '创建者');
            $this->userMorphs('updater', '更新者');
            $this->timestamps();
        });


        Blueprint::macro('category', function (?string $comment = null) {

            /***
             * @var Blueprint $this
             */
            $this->unsignedBigInteger('id')->primary()->comment('ID');
            $this->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $this->string('name')->comment('名称');
            $this->string('slug')->nullable()->comment('标记');
            $this->string('description')->nullable()->comment('描述');
            $this->string('cluster')->nullable()->comment('群簇');
            $this->bigInteger('sort')->default(0)->comment('排序');
            $this->boolean('is_leaf')->default(false)->comment('是否叶子');
            $this->boolean('is_show')->default(false)->comment('是否展示');
            $this->string('status', 32)->comment(UniversalStatusEnum::comments('状态'));
            $this->string('image')->nullable()->comment('图片');
            $this->string('icon')->nullable()->comment('图标');
            $this->string('color')->nullable()->comment('颜色');
            $this->json('extra')->nullable()->comment('扩展字段');


            $this->operator();
            $this->softDeletes();

            $this->index(['parent_id'], 'idx_parent');
            $this->index(['name'], 'idx_name');


            if ($comment) {
                $this->comment($comment);
            }
        });


        Blueprint::macro('approval', function () {
            $this->enum('approval_status', ApprovalStatusEnum::values())
                 ->nullable()->comment(ApprovalStatusEnum::comments('审批状态'));
            $this->timestamp('approval_time')->nullable()->comment('审批时间');
            $this->string('approval_message')->nullable()->comment('信息');
        });
    }
}