<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receiver_accounts',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->unsignedBigInteger('settle_receiver_id')->comment('结算接收方ID');
                $table->string('channel_code')->comment('渠道编码');
                $table->string('channel_merchant_id')->comment('渠道商户号');
                $table->string('settle_account_type')->comment(AccountTypeEnum::comments('账户类型'));
                $table->string('settle_account')->comment('账号');
                $table->timestamps();
                $table->unique([
                    'settle_receiver_id', 'channel_code', 'channel_merchant_id'
                ], 'uk_channel_account');
                $table->comment('商户结算接收方账户信息');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix',
                'jasmine_').'payment_settle_receiver_accounts');
    }
};
