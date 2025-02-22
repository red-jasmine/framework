<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {

        Schema::create('user_vip_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->string('app_id', 32)->comment('应用ID');
            $table->string('type', 32)->comment('类型');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('过期时间');
            $table->integer('time_value')->comment('时间');
            $table->string('time_unit')->comment('时间单位');
            $table->dateTime('order_time')->comment('开通时间');

            $table->string('payment_type')->nullable()->comment('支付类型');
            $table->string('payment_id')->nullable()->comment('支付单');

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->comment('用户VIP订单表');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_vip_orders');
    }

};
