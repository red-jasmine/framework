<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性ID');
            $table->unsignedBigInteger('group_id')->default(0)->comment('属性组ID');
            $table->enum('type', PropertyTypeEnum::values())->comment(PropertyTypeEnum::comments('类型'));
            $table->string('name')->comment('名称');
            $table->string('unit', 10)->nullable()->comment('单位');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->enum('status', PropertyStatusEnum::values())->comment(PropertyStatusEnum::comments('状态'));
            $table->json('expands')->nullable()->comment('扩展信息');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-属性表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_properties');
    }
};
