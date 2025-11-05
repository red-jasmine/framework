<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->string('code', 2)->primary()->comment('国家代码 ISO 3166-1 alpha-2');
            $table->string('iso_alpha_3', 3)->unique()->comment('国家代码 ISO 3166-1 alpha-3');
            $table->string('name')->comment('名称');
            $table->string('region', 64)->nullable()->comment('大区');
            $table->string('currency', 3)->comment('货币');
            $table->timestamps();
            $table->index('name', 'idx_name');
            $table->index('region', 'idx_region');
            $table->comment('国家地区表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('countries');
    }
};
