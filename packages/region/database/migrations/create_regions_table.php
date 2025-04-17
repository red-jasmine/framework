<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Region\Domain\Enums\RegionLevelEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->string('code')->primary()->comment('编码');
            $table->string('name')->comment('名称');
            $table->string('level', 32)->comment(RegionLevelEnum::comments('级别'));
            $table->string('parent_code')->nullable()->comment('父级编码');
            $table->string('area_code')->nullable()->comment('区号');
            $table->index('parent_code', 'idx_parent_code');
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
