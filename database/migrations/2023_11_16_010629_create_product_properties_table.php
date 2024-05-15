<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性ID');
            $table->unsignedBigInteger('group_id')->default(0)->comment('属性组ID');
            $table->string('name')->comment('名称');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->comment(PropertyStatusEnum::comments('状态'));
            $table->json('extend_info')->nullable()->comment('扩展信息');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');

            $table->timestamps();
            $table->comment('商品-属性表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_properties');
    }
};
