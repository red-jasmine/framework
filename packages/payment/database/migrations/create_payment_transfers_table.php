<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('payment_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('表ID');



            $table->timestamps();
            $table->comment('支付-转账');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('payment_transfers');
    }
};
