<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type')->comment('所有者类型');
            $table->string('owner_id')->comment('所有者ID');
            $table->string('market')->default('default')->comment('市场标识');
            $table->enum('status', ['active', 'expired', 'converted', 'cleared'])->default('active')->comment('购物车状态');
            $table->operator();
            $table->comment('购物车');
            $table->index(['owner_id', 'owner_type', 'market',], 'idx_owner_market');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('shopping_carts');
    }
}; 