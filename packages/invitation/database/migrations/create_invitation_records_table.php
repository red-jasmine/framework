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
        Schema::create('invitation_records', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('invitation_code_id')->comment('邀请码ID');
            $table->string('invitation_code', 32)->comment('邀请码');


            // 被邀请人信息
            $table->string('invitee_type', 32)->comment('被邀请人类型');
            $table->string('invitee_id', 64)->comment('被邀请人ID');
            $table->string('invitee_nickname', 100)->nullable()->comment('被邀请人昵称');

            // 邀请上下文
            $table->json('context')->nullable()->comment('邀请上下文信息');
            $table->string('target_url', 1000)->nullable()->comment('邀请链接目标URL');
            $table->string('target_type', 50)->nullable()->comment('邀请链接目标类型');


            // 时间信息
            $table->timestamp('invited_at')->comment('邀请时间');
            $table->timestamp('completed_at')->nullable()->comment('完成时间');

            // 操作人信息
            $table->operator();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('invitation_records');
    }
}; 