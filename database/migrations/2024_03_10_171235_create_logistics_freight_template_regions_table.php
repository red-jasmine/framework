<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('logistics_freight_template_fee_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->comment('模板ID');
            $table->text('regions')->comment('区域');
            $table->decimal('start_standard')->comment('首件');
            $table->decimal('start_fee')->comment('首件费用');
            $table->decimal('add_standard')->comment('续件');
            $table->decimal('add_fee')->comment('续件费用');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('运费模板-收费区域表');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('logistics_freight_template_fee_regions');
    }
};
