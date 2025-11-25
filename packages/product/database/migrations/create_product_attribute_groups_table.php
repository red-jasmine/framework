<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;

return new class extends Migration {
    public function up() : void
    {

        Schema::create('product_attribute_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性分组ID');
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->comment(ProductAttributeStatusEnum::comments('状态'));

            $table->operator();
            $table->softDeletes();

            $table->comment('商品-属性分组表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_attribute_groups');
    }
};
