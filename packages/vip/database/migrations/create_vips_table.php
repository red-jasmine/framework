<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Vip\Domain\Models\Enums\VipStatusEnum;

return new class extends Migration {
    public function up()
    {
        Schema::create('vips', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('biz', 64)->comment('业务');
            $table->string('type', 32)->comment('类型');
            $table->tinyInteger('level')->default(1)->comment('等级');
            $table->string('name', 32)->comment('名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('description')->nullable()->comment('描述');
            $table->string('status')->default(VipStatusEnum::ENABLE)->comment(VipStatusEnum::comments('状态'));
            $table->json('extra')->nullable()->comment('扩展参数');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->unique(['biz', 'type'], 'uk_vip_type');
            $table->comment('vip 表');
        });
    }
    public function down()
    {
        Schema::dropIfExists('vips');
    }

};
