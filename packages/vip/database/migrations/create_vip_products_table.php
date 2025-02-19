<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Vip\Domain\Models\Enums\VipProductStatusEnum;

return new class extends Migration {
    public function up()
    {
        Schema::create('vip_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('app_id', 32)->comment('应用ID');
            $table->string('type', 32)->comment('类型');
            $table->string('name', 64)->comment('名称');
            $table->integer('time_value')->comment('时间');
            $table->string('time_unit', 32)->comment('时间单位');
            $table->string('amount_currency', 10)->comment('金额货币');
            $table->bigInteger('amount_value')->default(0)->comment('金额值');
            $table->string('description')->nullable()->comment('描述');
            $table->string('status')->default(VipProductStatusEnum::ENABLE)->comment(VipProductStatusEnum::comments('状态'));
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            
            $table->comment('VIP 商品表');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vip_products');
    }

};
