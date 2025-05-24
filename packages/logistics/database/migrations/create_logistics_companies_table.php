<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Logistics\Domain\Models\Enums\Companies\CompanyTypeEnum;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        // 运费
        Schema::create('logistics_companies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('编码');
            $table->string('name')->comment('名称');
            $table->string('logo')->nullable()->comment('图标');
            $table->string('tel')->nullable()->comment('电话');
            $table->string('url')->nullable()->comment('网址');
            $table->string('type')->comment(CompanyTypeEnum::comments('类型'));
            $table->string('status')->default(UniversalStatusEnum::ENABLE)->comment(UniversalStatusEnum::comments('状态'));
            $table->operator();
            $table->comment('物流-公司表');
            $table->unique('code', 'uk_code');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('logistics_companies');
    }
};
