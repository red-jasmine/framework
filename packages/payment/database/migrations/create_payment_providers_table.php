<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix','jasmine_') . 'payment_providers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('name')->comment('名称');
            $table->string('status')->comment('状态');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator','idx_creator');
            $table->nullableMorphs('updater','idx_updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('服务商');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix','jasmine_') . 'payment_providers');
    }
};
