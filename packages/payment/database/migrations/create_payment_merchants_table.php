<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\MerchantTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'payment_merchants', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('商户ID');
            $table->morphs('owner');
            $table->string('name')->comment('名称');
            $table->string('short_name')->comment('短名称');
            $table->string('type')->comment(MerchantTypeEnum::comments('类型'));
            $table->unsignedBigInteger('isv_id')->nullable()->comment('服务商ID');
            $table->string('status')->comment(MerchantStatusEnum::comments('状态'));
            $table->string('remarks')->nullable()->comment('备注');
            $table->operator();
            $table->softDeletes();
            $table->comment('支付商户');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'payment_merchants');
    }
};
