<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('department_managers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('department_id')->index()->comment('部门ID');
            $table->unsignedBigInteger('member_id')->index()->comment('成员ID');
            $table->boolean('is_primary')->default(false)->index()->comment('是否主要负责人');
            $table->timestamp('started_at')->nullable()->index()->comment('任命开始时间');
            $table->timestamp('ended_at')->nullable()->index()->comment('任命结束时间(NULL为当前)');
            $table->timestamps();

            $table->index(['department_id', 'member_id']);
            $table->comment('部门管理者历史表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('department_managers');
    }
};


