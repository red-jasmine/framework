<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoters', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 32)->comment('所属人类型');
            $table->string('owner_id', 64)->comment('所属人ID');
            $table->unsignedTinyInteger('level')->default(0)->comment('等级');
            $table->unsignedBigInteger('group_id')->nullable()->comment('所属分组ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('所属上级ID');
            $table->string('status', 32)->comment(PromoterStatusEnum::comments('状态'));
            $table->unsignedBigInteger('team_id')->nullable()->comment('所属团队ID');
            $table->string('owner_id', 64)->comment('所属人ID');
            $table->operator();
            $table->comment('分销员');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoters');
    }
};
