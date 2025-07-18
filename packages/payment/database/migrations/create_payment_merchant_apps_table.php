<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_merchant_apps',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->string('name')->comment('名称');
                $table->string('status')->comment(MerchantAppStatusEnum::comments('状态'));
                $table->string('remarks')->nullable()->comment('备注');
                $table->string('sign_type')->nullable()->comment('签名类型');
                $table->text('system_private_key')->nullable()->comment('系统私钥');
                $table->text('system_public_key')->nullable()->comment('系统公钥');
                $table->text('app_public_key')->nullable()->comment('应用公钥');
                $table->text('app_private_key')->nullable()->comment('应用私钥');

                $table->operator();
                $table->softDeletes();
                $table->comment('支付应用表');
            });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_merchant_apps');
    }
};
