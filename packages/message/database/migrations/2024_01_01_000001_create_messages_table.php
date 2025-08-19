<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('biz', 64)->comment('业务线');
            $table->unsignedBigInteger('category_id')->nullable()->comment('消息分类ID');
            $table->userMorphs('owner', '接收者', false, false);
            $table->userMorphs('sender', '发送者', true, false);
            $table->unsignedBigInteger('template_id')->nullable()->comment('消息模板ID');
            $table->string('title')->comment('消息标题');
            $table->text('content')->comment('消息内容');
            $table->json('data')->nullable()->comment('消息数据');
            $table->json('attachments')->nullable()->comment('附件');
            $table->userMorphs('source', '来源', true, false);
            $table->enum('type', ['notification', 'alert', 'reminder'])->default('notification')->comment('消息类型');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->comment('优先级');
            $table->enum('status', ['unread', 'read', 'archived'])->default('unread')->comment('消息状态');
            $table->timestamp('read_at')->nullable()->comment('阅读时间');
            $table->json('channels')->nullable()->comment('推送渠道配置');
            $table->enum('push_status', ['pending', 'sent', 'failed'])->default('pending')->comment('推送状态');
            $table->boolean('is_urgent')->default(false)->comment('是否强提醒');
            $table->boolean('is_burn_after_read')->default(false)->comment('是否阅后即焚');
            $table->timestamp('expires_at')->nullable()->comment('过期时间');
            $table->operator();
            $table->softDeletes();

            // 索引


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('messages');
    }
};
