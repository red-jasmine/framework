<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SettleRelationTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receivers',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->unsignedBigInteger('system_merchant_app_id')->comment('系统商户ID');
                $table->string('receiver_type', 32)->comment('接收方用户类型');
                $table->string('receiver_id', 64)->comment('接收方用户ID');
                $table->string('channel_code', 64)->comment('渠道编码');
                $table->string('channel_merchant_id', 64)->comment('渠道商户号');
                $table->text('name')->comment('名称');
                $table->string('account_type')->comment(AccountTypeEnum::comments('账户类型'));
                $table->string('account')->comment('账号');
                $table->string('cert_type')->nullable()->comment(CertTypeEnum::comments('证件类型'));

                $table->string('relation_type')->default(SettleRelationTypeEnum::CUSTOM)->comment(SettleRelationTypeEnum::comments('关系类型'));
                $table->text('cert_no')->nullable()->comment('收款方证件号');
                $table->string('creator_type', 32)->nullable();
                $table->string('creator_id', 64)->nullable();
                $table->string('updater_type', 32)->nullable();
                $table->string('updater_id', 64)->nullable();
                $table->timestamps();
                $table->unique([
                    'system_merchant_app_id',
                    'receiver_type', 'receiver_id', 'channel_code', 'channel_merchant_id'
                ], 'uk_merchant_app_channel');

                $table->comment('支付-结算分账接收方');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix',
                'jasmine_').'payment_settle_receivers');
    }
};
