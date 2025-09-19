<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('member_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('member_id')->index()->comment('成员ID');
            $table->unsignedBigInteger('department_id')->index()->comment('部门ID');
            $table->boolean('is_primary')->default(false)->index()->comment('是否主部门');
            $table->timestamp('started_at')->nullable()->index()->comment('任职开始时间');
            $table->timestamp('ended_at')->nullable()->index()->comment('任职结束时间(NULL为当前)');
            $table->timestamps();

            $table->index(['member_id', 'department_id']);
            $table->comment('成员-部门任职历史表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('member_departments');
    }
};


