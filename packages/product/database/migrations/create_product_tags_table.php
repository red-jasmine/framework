<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_tags', function (Blueprint $table) {

            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->index(['owner_id', 'owner_type'], 'idx_owner');
            $table->category('商品-标签');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_tags');
    }
};
