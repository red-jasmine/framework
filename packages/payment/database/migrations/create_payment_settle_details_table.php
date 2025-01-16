<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_details',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('settle_no')->comment('结算号');
                // 保留分账数据
                $table->unsignedBigInteger('system_merchant_app_id')->comment('系统商户ID');
                $table->string('receiver_type', 32)->comment('接收方用户类型');
                $table->string('receiver_id', 64)->comment('接收方用户ID');
                $table->string('settle_receiver_id')->comment('结算接收方ID');
                $table->string('amount_currency')->comment('货币');
                $table->bigInteger('amount_value')->default(0)->comment('金额');
                $table->string('settle_status')->comment('状态');
                $table->text('name')->comment('名称');
                $table->string('account_type')->comment(AccountTypeEnum::comments('账户类型'));
                $table->string('account')->comment('账号');
                $table->string('cert_type')->nullable()->comment(CertTypeEnum::comments('证件类型'));
                $table->string('subject')->nullable()->comment('交易标题');
                $table->string('description')->nullable()->comment('说明');
                $table->string('message')->nullable()->comment('货币');

                $table->timestamp('create_time')->nullable()->comment('创建时间');
                $table->timestamp('settle_time')->nullable()->comment('结算时间');
                $table->string('channel_settle_detail_no')->nullable()->comment('渠道结算明细单号');
                $table->timestamps();
                $table->comment('支付-结算单-明细');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_details');
    }
};
