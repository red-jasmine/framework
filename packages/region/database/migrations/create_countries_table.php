<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->comment('ISO3');
            $table->string('name', 64);
            $table->string('native', 64)->nullable();
            $table->string('region', 64)->nullable();
            $table->string('currency', 64);
            $table->string('phone_code', 32)->nullable()->comment('电话区号');
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->json('timezones')->nullable();
            $table->json('translations')->nullable();
            $table->timestamps();
            $table->index('code', 'idx_code');
            $table->index('name', 'idx_name');
            $table->comment('国家地区表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('countries');
    }
};
