<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundGoodsStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundPhaseEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('order_refund_products_extension', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('售后单号');
            $table->string('description')->nullable()->comment('描述');
            $table->json('images')->nullable()->comment('图片');
            $table->string('reject_reason')->nullable()->comment('拒绝理由');
            $table->json('extra')->nullable()->comment('扩展');
            $table->string('seller_remarks')->nullable()->comment('卖家备注');
            $table->string('buyer_remarks')->nullable()->comment('买家备注');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-退款-信息表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('order_refund_products_extension');
    }
};
