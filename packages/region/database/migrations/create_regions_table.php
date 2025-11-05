<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Region\Domain\Enums\RegionTypeEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->string('code', 64)->primary()->comment('代码');
            $table->string('parent_code', 64)->nullable()->comment('父级编码');
            $table->string('country_code', 2)->comment('国家代码 ISO 3166-1 alpha-2');
            $table->enum('type', RegionTypeEnum::values())->comment(RegionTypeEnum::comments('类型'));
            $table->string('name')->comment('名称');
            $table->string('region', 64)->nullable()->comment('大区');
            $table->unsignedTinyInteger('level')->default(0)->comment('树层级');
            $table->timestamps();
            $table->unique(['country_code', 'code']);
            $table->index(['country_code', 'parent_code',], 'idx_parent_code');
            $table->comment('行政区划表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('regions');
    }
};
