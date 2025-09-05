<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityOrderStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('promotion_activity_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('activity_id')->comment('活动ID');

            $table->userMorphs('seller', '卖家', false);
            $table->string('product_type')->comment('商品源类型');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('sku_id')->nullable()->comment('SKU ID');
            $table->userMorphs('user', '用户', false);

            // 参与信息
            $table->string('order_no', 64)->nullable()->comment('订单号');
            $table->integer('quantity')->default(1)->comment('参与数量');
            $table->decimal('amount', 10, 2)->comment('参与金额');
            $table->timestamp('participated_at')->nullable()->comment('参与时间');


            // 状态
            $table->enum('status', ActivityOrderStatusEnum::values())
                  ->default(ActivityOrderStatusEnum::PARTICIPATED)
                  ->comment(ActivityOrderStatusEnum::comments('状态'));

            // 操作信息
            $table->operator();
            $table->softDeletes();

            // 索引

            $table->comment('活动参与订单表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('promotion_activity_orders');
    }
};
