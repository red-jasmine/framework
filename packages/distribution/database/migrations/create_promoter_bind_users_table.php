<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_bind_users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('user_type', 32)->comment('用户类型');
            $table->string('user_id', 64)->comment('用户ID');
            $table->unsignedBigInteger('promoter_id')->comment('分销员ID');
            $table->string('status')->comment(PromoterBindUserStatusEnum::comments('状态'));
            $table->timestamp('bound_time')->comment('绑定时间');
            $table->timestamp('activation_time')->comment('激活时间');
            $table->timestamp('protection_time')->comment('保护时间');
            $table->timestamp('expiration_time')->comment('过期时间');
            $table->timestamp('unbound_time')->nullable()->comment('解绑时间');
            $table->string('unbound_type')->nullable()->comment('解绑类型');
            $table->operator();
            $table->comment('分销绑定用户');

            $table->index(['user_id', 'user_type', 'status'], 'idx_uses');
            $table->index(['promoter_id', 'status'], 'idx_promoter');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_bind_users');
    }
};
