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
            $table->id();
            $table->string('code', 64)->unique()->comment('代码');
            $table->string('name', 64)->comment('名称');
            $table->string('level', 32)->comment(RegionLevelEnum::comments('级别'));
            $table->string('country_code', 3)->comment('国家地区代码');
            $table->string('parent_code', 64)->default('0')->comment('父级编码');
            $table->string('phone_code', 64)->nullable()->comment('电话区号');
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->json('timezones')->nullable();
            $table->json('translations')->nullable();
            $table->timestamps();
            $table->index('parent_code', 'idx_parent_code');
            $table->index('country_code', 'idx_country_code');
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
