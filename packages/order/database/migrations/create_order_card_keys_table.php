<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'order_card_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('app_id', 64)->comment('应用ID');
            $table->string('order_no',64)->comment('订单号');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->string('seller_id',64)->comment('卖家ID');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->string('buyer_id',64)->comment('买家类型');

            $table->string('entity_type',32)->comment(EntityTypeEnum::comments('对象类型'));
            $table->string('entity_id',64)->comment('对象单号');

            $table->string('order_product_no')->comment('订单商品项单号');
            $table->unsignedBigInteger('quantity')->default(1)->comment('数量');
            $table->string('content_type')->default(OrderCardKeyContentTypeEnum::TEXT)->comment(OrderCardKeyContentTypeEnum::comments('状态'));
            $table->text('content')->nullable()->comment('内容');
            $table->string('source_type')->nullable()->comment('来源类型');
            $table->string('source_id')->nullable()->comment('来源类型');
            $table->string('status')->nullable()->comment(OrderCardKeyStatusEnum::comments('状态'));
            $table->operator();
            $table->softDeletes();
            $table->comment('订单-卡密表');

            $table->index([ 'entity_id', 'entity_type' ], 'idx_entity');
            $table->index([ 'seller_id', 'seller_type', 'order_no', 'order_product_no' ], 'idx_seller');
            $table->index([ 'buyer_id', 'buyer_type', 'order_no', 'order_product_no' ], 'idx_buyer');


        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'order_card_keys');
    }
};
