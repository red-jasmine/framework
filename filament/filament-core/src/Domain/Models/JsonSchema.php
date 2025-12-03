<?php

namespace RedJasmine\FilamentCore\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property int $id
 * @property string $name
 * @property ?string $slug
 * @property ?string $title
 * @property ?string $description
 * @property array $schema
 * @property string $version
 * @property ?array $extra
 */
class JsonSchema extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'title',
        'description',
        'schema',
        'version',
        'extra',
    ];

    protected $casts = [
        'schema' => 'array',
        'extra' => 'array',
    ];

    /**
     * 验证 JSON Schema 结构
     */
    public function validateSchema(): bool
    {
        // 这里可以添加 JSON Schema 验证逻辑
        // 可以使用 json-schema-validator 库
        return !empty($this->schema);
    }

    /**
     * 获取完整的 JSON Schema（包含 $schema）
     */
    public function getFullSchema(): array
    {
        $schema = $this->schema;
        $schema['$schema'] = 'http://json-schema.org/draft-07/schema#';
        $schema['$id'] = $this->slug ? "/schemas/{$this->slug}" : null;

        if ($this->title) {
            $schema['title'] = $this->title;
        }

        if ($this->description) {
            $schema['description'] = $this->description;
        }

        return $schema;
    }
}

