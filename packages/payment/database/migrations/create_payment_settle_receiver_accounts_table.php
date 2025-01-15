<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receivers',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->string('settle_account_type')->comment('账号类型');
                $table->string('settle_account')->comment('账号');
                $table->timestamps();
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receivers');
    }
};
