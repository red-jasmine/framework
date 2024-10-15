<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('logistics_freight_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->morphs('owner');
            $table->string('name')->comment('模板名称');
            $table->string('charge_type')->comment('计费类型');
            $table->unsignedBigInteger('is_free')->comment('是否包邮');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('运费模板表');

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('logistics_freight_templates');
    }
};
