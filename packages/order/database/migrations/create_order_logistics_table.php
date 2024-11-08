<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_logistics', function (Blueprint $table) {
            $table->id();
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家ID');
            $table->unsignedBigInteger('order_id')->comment('订单ID');
            $table->string('entity_type')->comment(EntityTypeEnum::comments('对象类型'));
            $table->unsignedBigInteger('entity_id')->comment('对象单号');

            $table->string('order_product_id')->nullable()->comment('订单商品项单号');
            $table->string('shipper', 32)->comment(LogisticsShipperEnum::comments('发货方'));
            $table->string('status', 32)->comment(LogisticsStatusEnum::comments('状态'));
            $table->string('express_company_code')->comment('快递公司代码');
            $table->string('express_no')->comment('快递单号');
            $table->timestamp('shipping_time')->nullable()->comment('发货时间');
            $table->timestamp('collect_time')->nullable()->comment('揽收时间');
            $table->timestamp('dispatch_time')->nullable()->comment('派送时间');
            $table->timestamp('signed_time')->nullable()->comment('签收时间');
            // 收件码
            // 取件码
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-物流表');

            $table->index([ 'entity_id', 'entity_type' ], 'idx_entity');
            $table->index([ 'seller_id', 'seller_type', 'order_id', ], 'idx_seller');
            $table->index([ 'buyer_id', 'buyer_type', 'order_id', ], 'idx_buyer');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_logistics');
    }
};
