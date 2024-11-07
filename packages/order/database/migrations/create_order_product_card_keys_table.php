<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_product_card_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');

            $table->string('seller_type')->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('buyer_type')->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家类型');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->unsignedBigInteger('order_product_id')->comment('订单商品项单号');
            $table->unsignedBigInteger('num')->default(1)->comment('数量');
            $table->string('content_type')->default(OrderCardKeyContentTypeEnum::TEXT)->comment(OrderCardKeyContentTypeEnum::comments('状态'));
            $table->text('content')->nullable()->comment('内容');
            $table->string('source_type')->nullable()->comment('来源类型');
            $table->string('source_id')->nullable()->comment('来源类型');
            $table->string('status')->nullable()->comment(OrderCardKeyStatusEnum::comments('状态'));
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-卡密表');
            $table->index([ 'seller_id', 'seller_type', 'order_id', 'order_product_id' ], 'idx_seller');
            $table->index([ 'buyer_id', 'buyer_type', 'order_id', 'order_product_id' ], 'idx_buyer');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_product_card_keys');
    }
};
