<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('logistics_freight_template_free_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->comment('模板ID');
            $table->text('regions')->comment('区域');
            $table->unsignedInteger('quantity')->default(0)->comment('数量');
            $table->decimal('amount')->default(0)->comment('金额');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('运费模板-包邮区域表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('logistics_freight_template_free_regions');
    }
};
