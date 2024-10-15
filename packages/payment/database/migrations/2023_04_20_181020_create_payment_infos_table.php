<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('表ID');

            // 支付用户信息类

            $table->string('payer_open_id')->nullable()->comment('买家OPENID');
            $table->string('request_url')->nullable()->comment('请求地址');
            $table->string('return_url')->nullable()->comment('成功重定向地址');
            $table->string('notify_url')->nullable()->comment('业务通知地址');
            $table->json('pass_back_params')->nullable()->comment('回传参数');
            $table->json('extends')->nullable()->comment('扩展参数');

            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_infos');
    }
};
