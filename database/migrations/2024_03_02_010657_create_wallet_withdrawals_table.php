<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_withdrawals', function (Blueprint $table) {
            $table->id();


            $table->timestamps();
            $table->comment('钱包-提现单');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_withdrawals');
    }
};
