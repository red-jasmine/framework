<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('biz', 32)->comment('业务线');
            $table->string('owner_type', 32)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');
            $table->unsignedBigInteger('category_id')->nullable()->comment('公告分类ID');
            $table->string('title', 255)->comment('公告标题');
            $table->string('image')->nullable()->comment('公告封面');
            $table->string('content_type')->default(ContentTypeEnum::TEXT)->comment('内容类型');
            $table->longText('content')->comment('公告内容');
            $table->json('scopes')->comment('人群范围');
            $table->json('channels')->comment('发布渠道');
            $table->timestamp('publish_time')->nullable()->comment('发布时间');
            $table->enum('status',
                AnnouncementStatus::values())->default(AnnouncementStatus::DRAFT)->comment(AnnouncementStatus::comments('状态'));
            $table->json('attachments')->comment('附件信息');

            $table->boolean('is_force_read')->default(false)->comment('是否强制阅读');

            $table->approval();
            $table->operator();
            $table->softDeletes();

            $table->comment('公告表');
            // 索引
            $table->index(['biz', 'owner_type', 'owner_id'], 'idx_biz_owner');
            $table->index('category_id', 'idx_category');
            $table->index('status', 'idx_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('announcements');
    }
};
