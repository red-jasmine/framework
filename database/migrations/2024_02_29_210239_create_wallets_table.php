<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->morphs('owner');
            $table->string('type', 30)->comment('账户类型');
            $table->decimal('balance', 12, 2)->default(0)->comment('余额');
            $table->decimal('freeze', 12, 2)->default(0)->comment('冻结');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态');
            $table->timestamps();
            $table->comment('钱包表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallets');
    }
};
