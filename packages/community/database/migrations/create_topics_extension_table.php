<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;


return new class extends Migration {
    public function up() : void
    {
        Schema::create('topics_extension', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('content_type')->default(ContentTypeEnum::TEXT)->comment('内容类型');
            $table->longText('content')->comment('内容');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down() : void
    {
        Schema::dropIfExists('topics_extension');
    }
};
