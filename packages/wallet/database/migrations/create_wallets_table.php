<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('type', 30)->comment('账户类型');
            $table->decimal('balance', 12)->default(0)->comment('余额');
            $table->decimal('freeze', 12)->default(0)->comment('冻结');
            $table->string('status')->default(1)->comment('状态');
            $table->timestamps();
            $table->comment('钱包表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallets');
    }
};
