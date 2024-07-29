<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;

return new class extends Migration {
    public function up() : void
    {

        Schema::create('product_property_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性组ID');
            $table->string('name')->comment('名称');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->comment(PropertyStatusEnum::comments('状态'));
            $table->json('expands')->nullable()->comment('扩展信息');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-属性分组表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_property_groups');
    }
};
