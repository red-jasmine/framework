<?php

namespace RedJasmine\FilamentCore\Forms\Components;

use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use RedJasmine\FilamentCore\Domain\Models\Enums\JsonSchemaTypeEnum;

class JsonSchemaBuilder extends Field
{
    protected string $view = 'red-jasmine-filament-core::forms.components.json-schema-builder';

    protected function setUp() : void
    {
        parent::setUp();

        // 设置默认值
        $this->default([]);

        // 处理状态水合（从数据库加载）
        $this->afterStateHydrated(function (JsonSchemaBuilder $component, $state) {
            if (is_string($state)) {
                $decoded = json_decode($state, true);
                $component->state($decoded !== null ? $decoded : []);
            } elseif (is_array($state) && !empty($state)) {
                // 如果已经是构建器格式（有 type 或 properties 键），直接使用
                // 如果是 JSON Schema 格式，需要转换为构建器格式
                if (isset($state['type']) && (isset($state['properties']) || isset($state['items']))) {
                    // 已经是构建器格式或标准 JSON Schema 格式
                    $component->state($state);
                } else {
                    // 空数组或无效格式，使用默认值
                    $component->state([]);
                }
            } else {
                $component->state([]);
            }
        });

        // 处理状态脱水（保存到数据库）
        $this->dehydrateStateUsing(function ($state) {

            if (is_array($state) && !empty($state)) {
                // 检查是否是构建器格式（有 properties 数组且包含 key 字段）
                $isBuilderFormat = isset($state['properties']) && is_array($state['properties'])
                                   && !empty($state['properties'])
                                   && isset($state['properties'][0]['key']);

                if ($isBuilderFormat) {
                    // 构建器格式，转换为标准 JSON Schema
                    return $this->transformToJsonSchema($state);
                } else {
                    // 已经是标准 JSON Schema 格式，直接返回
                    // 确保有 $schema 字段
                    if (!isset($state['$schema'])) {
                        $state['$schema'] = 'http://json-schema.org/draft-07/schema#';
                    }

                    return $state;
                }
            }

            // 空数据，返回默认的 JSON Schema
            return [
                '$schema'    => 'http://json-schema.org/draft-07/schema#',
                'type'       => 'object',
                'properties' => [],
            ];
        });

        // 设置内部 schema
        $this->schema($this->getDefaultSchema());
    }

    protected function getDefaultSchema() : array
    {
        return [
            Section::make('Schema 构建器')
                   ->description('使用可视化构建器创建 JSON Schema 结构')
                   ->schema([
                       TextInput::make('title')
                                ->label('标题')
                                ->maxLength(255)
                                ->helperText('JSON Schema 的标题'),

                       Textarea::make('description')
                               ->label('描述')
                               ->rows(3)
                               ->helperText('JSON Schema 的描述信息'),

                       Select::make('type')
                             ->label('根类型')
                             ->required()
                             ->default(JsonSchemaTypeEnum::OBJECT->value)
                             ->useEnum(JsonSchemaTypeEnum::class)
                             ->live()
                             ->helperText('选择 JSON Schema 的根类型'),

                       Repeater::make('properties')
                               ->label('属性')
                               ->visible(fn(Get $get) => in_array($get('type')->value, ['object', 'array']))
                           ->dehydrated()
                               ->schema([
                                   TextInput::make('key')
                                            ->label('属性名')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('属性的键名'),

                                   Select::make('type')
                                         ->label('类型')
                                         ->required()
                                         ->default(JsonSchemaTypeEnum::STRING->value)
                                         ->useEnum(JsonSchemaTypeEnum::class)
                                         ->live(),

                                   TextInput::make('title')
                                            ->label('标题')
                                            ->maxLength(255),

                                   Textarea::make('description')
                                           ->label('描述')
                                           ->rows(2),

                                   TextInput::make('default')
                                            ->label('默认值')
                                            ->helperText('默认值（JSON 格式）'),

                                   Checkbox::make('required')
                                           ->label('必填')
                                           ->default(false),

                                   // 字符串类型特有
                                   TextInput::make('minLength')
                                            ->label('最小长度')
                                            ->numeric()
                                            ->visible(fn(Get $get) => in_array($get('type'), ['string'])),

                                   TextInput::make('maxLength')
                                            ->label('最大长度')
                                            ->numeric()
                                            ->visible(fn(Get $get) => in_array($get('type'), ['string'])),

                                   TextInput::make('pattern')
                                            ->label('正则表达式')
                                            ->visible(fn(Get $get) => in_array($get('type'), ['string'])),

                                   Select::make('format')
                                         ->label('格式')
                                         ->options([
                                             'date'      => '日期',
                                             'time'      => '时间',
                                             'date-time' => '日期时间',
                                             'email'     => '邮箱',
                                             'uri'       => 'URI',
                                             'hostname'  => '主机名',
                                             'ipv4'      => 'IPv4',
                                             'ipv6'      => 'IPv6',
                                         ])
                                         ->visible(fn(Get $get) => in_array($get('type'), ['string'])),

                                   // 数值类型特有
                                   TextInput::make('minimum')
                                            ->label('最小值')
                                            ->numeric()
                                            ->visible(fn(Get $get) => in_array($get('type'), ['number', 'integer'])),

                                   TextInput::make('maximum')
                                            ->label('最大值')
                                            ->numeric()
                                            ->visible(fn(Get $get) => in_array($get('type'), ['number', 'integer'])),

                                   // 数组类型特有
                                   Select::make('items_type')
                                         ->label('数组项类型')
                                         ->useEnum(JsonSchemaTypeEnum::class)
                                         ->visible(fn(Get $get) => $get('type') === 'array'),

                                   // 枚举值
                                   Repeater::make('enum')
                                           ->label('枚举值')
                                           ->schema([
                                               TextInput::make('value')
                                                        ->label('值')
                                                        ->required(),
                                           ])
                                           ->defaultItems(0),

                                   // 嵌套对象/数组
                                   Repeater::make('properties')
                                           ->label('嵌套属性')
                                           ->schema(fn() => $this->getNestedPropertySchema())
                                           ->visible(fn(Get $get) => in_array($get('type'), ['object', 'array']))
                                           ->defaultItems(0),
                               ])
                               ->defaultItems(0)
                               ->itemLabel(fn(array $state) : ?string => $state['key'] ?? '新属性')
                               ->collapsible()
                               ->collapsed(),

                       Textarea::make('raw_schema')
                               ->label('原始 JSON Schema')
                               ->rows(10)
                               ->helperText('可以直接编辑 JSON Schema（JSON 格式）')
                               ->formatStateUsing(fn($state) => $state ? json_encode($state,
                                   JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '')
                               ->dehydrated(false),
                   ]),
        ];
    }

    protected function getNestedPropertySchema() : array
    {
        return [
            TextInput::make('key')
                     ->label('属性名')
                     ->required()
                     ->maxLength(255),

            Select::make('type')
                  ->label('类型')
                  ->required()
                  ->default(JsonSchemaTypeEnum::STRING->value)
                  ->useEnum(JsonSchemaTypeEnum::class)
                  ->live(),

            TextInput::make('title')
                     ->label('标题')
                     ->maxLength(255),

            Textarea::make('description')
                    ->label('描述')
                    ->rows(2),
        ];
    }

    /**
     * 将表单数据转换为 JSON Schema 格式
     */
    public function transformToJsonSchema(array $data) : array
    {
        $schema = [
            '$schema' => 'http://json-schema.org/draft-07/schema#',
        ];

        if (!empty($data['title'])) {
            $schema['title'] = $data['title'];
        }

        if (!empty($data['description'])) {
            $schema['description'] = $data['description'];
        }

        $type           = $data['type'] ?? 'object';
        $schema['type'] = $type;

        if ($type === 'object' && !empty($data['properties'])) {
            $schema['properties'] = [];
            $required             = [];

            foreach ($data['properties'] as $property) {
                $key = $property['key'] ?? null;
                if (!$key) {
                    continue;
                }

                $propertySchema             = $this->buildPropertySchema($property);
                $schema['properties'][$key] = $propertySchema;

                if (!empty($property['required'])) {
                    $required[] = $key;
                }
            }

            if (!empty($required)) {
                $schema['required'] = $required;
            }
        } elseif ($type === 'array' && !empty($data['properties'])) {
            $firstProperty = $data['properties'][0] ?? null;
            if ($firstProperty) {
                $schema['items'] = $this->buildPropertySchema($firstProperty);
            }
        }

        return $schema;
    }

    protected function buildPropertySchema(array $property) : array
    {
        $propertySchema = [
            'type' => $property['type'] ?? 'string',
        ];

        if (!empty($property['title'])) {
            $propertySchema['title'] = $property['title'];
        }

        if (!empty($property['description'])) {
            $propertySchema['description'] = $property['description'];
        }

        if (isset($property['default']) && $property['default'] !== '') {
            $propertySchema['default'] = $this->parseDefaultValue($property['default'], $property['type']);
        }

        // 字符串类型特有属性
        if ($property['type'] === 'string') {
            if (!empty($property['minLength'])) {
                $propertySchema['minLength'] = (int) $property['minLength'];
            }
            if (!empty($property['maxLength'])) {
                $propertySchema['maxLength'] = (int) $property['maxLength'];
            }
            if (!empty($property['pattern'])) {
                $propertySchema['pattern'] = $property['pattern'];
            }
            if (!empty($property['format'])) {
                $propertySchema['format'] = $property['format'];
            }
        }

        // 数值类型特有属性
        if (in_array($property['type'], ['number', 'integer'])) {
            if (isset($property['minimum']) && $property['minimum'] !== '') {
                $propertySchema['minimum'] = (float) $property['minimum'];
            }
            if (isset($property['maximum']) && $property['maximum'] !== '') {
                $propertySchema['maximum'] = (float) $property['maximum'];
            }
        }

        // 枚举值
        if (!empty($property['enum']) && is_array($property['enum'])) {
            $propertySchema['enum'] = array_map(fn($item) => $item['value'], $property['enum']);
        }

        // 嵌套对象/数组
        if (in_array($property['type'], ['object', 'array']) && !empty($property['properties'])) {
            if ($property['type'] === 'object') {
                $nestedProperties = [];

                foreach ($property['properties'] as $nestedProperty) {
                    $key = $nestedProperty['key'] ?? null;
                    if (!$key) {
                        continue;
                    }

                    $nestedProperties[$key] = $this->buildPropertySchema($nestedProperty);
                }

                $propertySchema['properties'] = $nestedProperties;
            } else {
                // 数组类型
                $firstNested = $property['properties'][0] ?? null;
                if ($firstNested) {
                    $propertySchema['items'] = $this->buildPropertySchema($firstNested);
                }
            }
        }

        return $propertySchema;
    }

    protected function parseDefaultValue(string $value, string $type) : mixed
    {
        if ($type === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($type === 'integer') {
            return (int) $value;
        }

        if ($type === 'number') {
            return (float) $value;
        }

        if (in_array($type, ['object', 'array'])) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : $value;
        }

        return $value;
    }
}
