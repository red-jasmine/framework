<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;

return new class extends Migration {
    public function up() : void
    {

        Schema::create('product_attribute_groups', function (Blueprint $table) {

            $table->category('商品-属性分组表');
            $table->comment('商品-属性分组表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_attribute_groups');
    }
};
