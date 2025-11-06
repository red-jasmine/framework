<?php

namespace RedJasmine\FilamentRegion\Forms\Components;

use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\FusedGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use RedJasmine\Region\Application\Services\Country\CountryService;
use RedJasmine\Region\Application\Services\Region\Queries\RegionChildrenQuery;
use RedJasmine\Region\Application\Services\Region\RegionApplicationService;
use RedJasmine\Region\Domain\Enums\RegionTypeEnum;

/**
 * 地区选择器组件
 *
 * 支持多级地区联动选择，层级和字段可自由配置
 * 支持自动保存选中选项的名称到指定字段
 * 支持国家选择器（默认启用）
 *
 * 使用示例：
 *
 * // 基本用法（默认带国家选择器）
 * RegionSelect::make()
 *     ->fields(['province_code', 'city_code', 'district_code'])
 *     ->labelFields(['province', 'city', 'district'])
 *     ->labels(['省份', '城市', '区县']);
 *
 * // 不显示国家选择器
 * RegionSelect::make()
 *     ->withoutCountry()
 *     ->fields(['province_code', 'city_code', 'district_code'])
 *     ->labelFields(['province', 'city', 'district']);
 *
 * // 自定义国家字段名
 * RegionSelect::make()
 *     ->countryFields('country_code', 'country_name')
 *     ->fields(['province_code', 'city_code']);
 *
 * // 快捷方法
 * RegionSelect::make()->provinceAndCity();  // 省市两级
 * RegionSelect::make()->withStreet();       // 省市区街道四级
 */
class RegionSelect extends FusedGroup
{
    /**
     * 树的深度（层级数）
     */
    protected int $depth = 3;

    /**
     * 是否显示国家选择器
     */
    protected bool $withCountry =  false;

    /**
     * 国家代码字段名
     */
    protected string $countryCodeField = 'country_code';

    /**
     * 国家名称字段名
     */
    protected string $countryNameField = 'country';
    /**
     * 字段名称数组
     */
    protected array $codeFields = ['province_code', 'city_code', 'district_code', 'street_code', 'village_code'];

    protected array $nameFields = ['province', 'city', 'district', 'street', 'village'];

    /**
     * 标签数组
     */
    protected array $labels = ['省份', '城市', '区县', '街道乡镇', '社区村庄'];

    /**
     * 国家代码
     */
    protected string|Closure $countryCode = 'CN';


    /**
     * 创建组件
     */
    public static function make(array|Closure $schema = [], int $depth = 3) : static
    {

        /**
         * @var RegionSelect $static
         */
        $static = app(static::class, ['schema' => []]);


        $static->configure();

        // 延迟构建 schema，避免在构造函数中创建导致循环引用
        //$static->buildSchema();


        $static->depth($depth);

        return $static;
    }

    /**
     * 设置树的深度（层级数）
     */
    public function depth(int $depth) : static
    {
        $totalColumns = $this->withCountry ? $depth + 1 : $depth;
        $this->columns($totalColumns);
        $this->depth = $depth;
        $this->buildSchema();
        return $this;
    }

    /**
     * 设置字段名称数组
     */
    public function fields(array $fields) : static
    {

        $this->codeFields = $fields;
        $this->depth      = count($fields);
        $this->buildSchema();
        return $this;
    }

    /**
     * 设置名称字段数组
     */
    public function labelFields(array $labelFields) : static
    {
        $this->nameFields = $labelFields;
        $this->buildSchema();
        return $this;
    }

    /**
     * 设置标签数组
     */
    public function labels(array $labels) : static
    {
        $this->labels = $labels;
        $this->buildSchema();
        return $this;
    }

    /**
     * 设置国家代码
     */
    public function countryCode(string|Closure $code) : static
    {
        $this->countryCode = $this->evaluate($code);
        $this->buildSchema();
        return $this;
    }

    /**
     * 启用国家选择器
     *
     * @param string|null $codeField 国家代码字段名
     * @param string|null $nameField 国家名称字段名
     */
    public function withCountry(?string $codeField = null, ?string $nameField = null) : static
    {
        $this->withCountry = true;

        if ($codeField) {
            $this->countryCodeField = $codeField;
        }

        if ($nameField) {
            $this->countryNameField = $nameField;
        }

        // 重新计算列数
        $totalColumns = $this->depth + 1;
        $this->columns($totalColumns);

        $this->buildSchema();
        return $this;
    }

    /**
     * 禁用国家选择器
     */
    public function withoutCountry() : static
    {
        $this->withCountry = false;

        // 重新计算列数
        $this->columns($this->depth);

        $this->buildSchema();
        return $this;
    }

    /**
     * 设置国家字段名
     */
    public function countryFields(string $codeField, string $nameField) : static
    {
        $this->countryCodeField = $codeField;
        $this->countryNameField = $nameField;
        $this->buildSchema();
        return $this;
    }

    /**
     * 默认中国
     */
    public function defaultChina() : static
    {
        $this->countryCode = 'CN';
        return $this;
    }

    /**
     * 快捷方法：只显示省市两级
     */
    public function provinceAndCity() : static
    {
        $this->depth      = 2;
        $this->codeFields = ['province_code', 'city_code'];
        $this->nameFields = ['province', 'city'];
        $this->labels      = ['省份', '城市'];
        $totalColumns = $this->withCountry ? 3 : 2;
        $this->columns($totalColumns);
        $this->buildSchema();
        return $this;
    }

    /**
     * 快捷方法：只显示省份
     */
    public function provinceOnly() : static
    {
        $this->depth      = 1;
        $this->codeFields = ['province_code'];
        $this->nameFields = ['province'];
        $this->labels      = ['省份'];
        $totalColumns = $this->withCountry ? 2 : 1;
        $this->columns($totalColumns);
        $this->buildSchema();
        return $this;
    }

    /**
     * 快捷方法：省市区街道四级
     */
    public function withStreet() : static
    {
        $this->depth      = 4;
        $this->codeFields = ['province_code', 'city_code', 'district_code', 'street_code'];
        $this->nameFields = ['province', 'city', 'district', 'street'];
        $this->labels      = ['省份', '城市', '区县', '街道'];
        $totalColumns = $this->withCountry ? 5 : 4;
        $this->columns($totalColumns);
        $this->buildSchema();
        return $this;
    }

    /**
     * 构建 Schema
     */
    protected function buildSchema() : void
    {
        $components = [];

        /** @var string $countryCode */
        $countryCode = $this->evaluate($this->countryCode);

        // 添加国家选择器
        if ($this->withCountry) {
            $components[] = $this->buildCountrySelect($countryCode);
            $components[] = Hidden::make($this->countryNameField);
        }

        // 动态生成各层级的选择器
        for ($level = 1; $level <= $this->depth; $level++) {
            $fieldIndex = $level - 1;

            // 检查字段和标签是否存在
            if (!isset($this->codeFields[$fieldIndex])) {
                continue;
            }

            $currentField     = $this->codeFields[$fieldIndex];
            $currentLabel     = $this->labels[$fieldIndex] ?? "Level {$level}";
            $currentLabelField = $this->nameFields[$fieldIndex] ?? null; // 名称字段
            $parentField      = $level === 1 ? null : $this->codeFields[$level - 2]; // 第一层无父级

            $components[] = $this->buildLevelSelect(
                $currentField,
                $currentLabel,
                $countryCode,
                $level,
                $parentField,
                $currentLabelField
            );

            // 添加隐藏的名称字段
            if ($currentLabelField) {
                $hiddenField = Hidden::make($currentLabelField);

                // 当层级超过3级时，Hidden字段也要跟随可见性
                if ($level > 3 && $parentField) {
                    $hiddenField->visible(function (Get $get) use ($parentField, $countryCode) {
                        return static::hasRegionOptions($get($parentField), $countryCode);
                    });
                }

                $components[] = $hiddenField;
            }
        }

        // 重新设置 schema
        $this->schema($components);
    }

    /**
     * 构建级联选择器
     *
     * @param  string  $field  当前字段名
     * @param  string  $label  当前标签名
     * @param  string  $countryCode  国家代码
     * @param  int  $level  当前层级（从1开始）
     * @param  string|null  $parentField  父级字段名（顶层为null）
     * @param  string|null  $labelField  名称字段名（用于存储选中选项的名称）
     */
    protected function buildLevelSelect(
        string $field,
        string $label,
        string $countryCode,
        int $level,
        ?string $parentField = null,
        ?string $labelField = null
    ) : Select {
        // 收集所有下级字段，用于清空
        $childFields = array_slice($this->codeFields, $level);
        // 收集所有下级名称字段，用于清空
        $childLabelFields = array_slice($this->nameFields, $level);

        $isTopLevel = $parentField === null;
        $withCountry = $this->withCountry;
        $countryCodeField = $this->countryCodeField;

        $select = Select::make($field)
                        ->label($label)
                        ->live()
                        ->afterStateUpdated(function (Set $set, Get $get, $state) use ($childFields, $childLabelFields, $labelField, $parentField, $countryCode, $withCountry, $countryCodeField) {
                            // 获取当前国家代码（如果启用国家选择器，则从表单中读取）
                            $currentCountryCode = $withCountry ? ($get($countryCodeField) ?? $countryCode) : $countryCode;

                            // 设置当前选项的名称字段
                            if ($labelField && $state) {
                                $options = $parentField
                                    ? static::getRegionOptions($get($parentField), $currentCountryCode)
                                    : static::getRegionOptions(null, $currentCountryCode);
                                $set($labelField, $options[$state] ?? null);
                            }

                            // 清空所有下级选择（code 和 name）
                            foreach ($childFields as $index => $childField) {
                                $set($childField, null);
                                // 同时清空对应的名称字段
                                if (isset($childLabelFields[$index])) {
                                    $set($childLabelFields[$index], null);
                                }
                            }
                        });

        // 顶层选择器配置
        if ($isTopLevel) {
            // 如果启用国家选择器，则需要动态获取国家代码
            if ($withCountry) {
                $select->options(function (Get $get) use ($countryCode, $countryCodeField) {
                        $currentCountryCode = $get($countryCodeField) ?? $countryCode;
                        return static::getRegionOptions(null, $currentCountryCode);
                    })
                    ->disabled(function (Get $get) use ($countryCodeField) {
                        return !$get($countryCodeField);
                    });
            } else {
                $select->preload()
                    ->options(static::getRegionOptions(null, $countryCode));
            }
            $select->required($this->isRequired());
        } // 子级选择器配置
        else {
            $select->options(function (Get $get) use ($parentField, $countryCode, $withCountry, $countryCodeField) {
                $parentCode = $get($parentField);
                if (!$parentCode) {
                    return [];
                }
                // 获取当前国家代码
                $currentCountryCode = $withCountry ? ($get($countryCodeField) ?? $countryCode) : $countryCode;
                return static::getRegionOptions($parentCode, $currentCountryCode);
            })
                   ->disabled(function (Get $get) use ($parentField) {
                       return !$get($parentField);
                   });

            // 层级1-3：使用默认必填规则
            // 层级4+：如果有数据则必填，否则不必填
            if ($level <= 3) {
                $select->required(true);
            } else {
                // 当层级超过3级且没有数据时隐藏选择器
                $select->visible(function (Get $get) use ($parentField, $countryCode, $withCountry, $countryCodeField) {
                    $currentCountryCode = $withCountry ? ($get($countryCodeField) ?? $countryCode) : $countryCode;
                    return static::hasRegionOptions($get($parentField), $currentCountryCode);
                });

                // 如果可见（有数据），则必填
                $select->required(function (Get $get) use ($parentField, $countryCode, $withCountry, $countryCodeField) {
                    $currentCountryCode = $withCountry ? ($get($countryCodeField) ?? $countryCode) : $countryCode;
                    return static::hasRegionOptions($get($parentField), $currentCountryCode);
                });
            }
        }

        return $select;
    }

    /**
     * 获取地区选项
     */
    protected static function getRegionOptions(?string $parentCode, string $countryCode) : array
    {
        $service = app(RegionApplicationService::class);

        // 构建查询对象，通过对象属性传递过滤条件
        $query = RegionChildrenQuery::from([
            'country_code' => $countryCode,
            'parent_code'  => $parentCode,
        ]);


        $regions = $service->children($query);

        $options = [];
        foreach ($regions as $region) {
            $options[$region['code']] = $region['name'];
        }

        return $options;
    }

    /**
     * 检查是否有可用的地区选项
     */
    protected static function hasRegionOptions(?string $parentCode, string $countryCode) : bool
    {
        if (!$parentCode) {
            return false;
        }
        $options = static::getRegionOptions($parentCode, $countryCode);
        return !empty($options);
    }

    /**
     * 构建国家选择器
     */
    protected function buildCountrySelect(string $defaultCountryCode) : Select
    {
        $allCodeFields = $this->codeFields;
        $allNameFields = $this->nameFields;

        return Select::make($this->countryCodeField)
            ->label('国家')
            ->searchable()
            ->preload()
            ->options(static::getCountryOptions())
            ->default($defaultCountryCode)
            ->live()
            ->afterStateUpdated(function (Set $set, Get $get, $state) use ($allCodeFields, $allNameFields) {
                // 设置国家名称
                if ($state) {
                    $options = static::getCountryOptions();
                    $set($this->countryNameField, $options[$state] ?? null);
                }

                // 清空所有地区选择（code 和 name）
                foreach ($allCodeFields as $index => $codeField) {
                    $set($codeField, null);
                    if (isset($allNameFields[$index])) {
                        $set($allNameFields[$index], null);
                    }
                }
            })
            ->required(true);
    }

    /**
     * 获取国家选项
     */
    protected static function getCountryOptions() : array
    {
        $locale         = app()->getLocale();
        $countryService = app(CountryService::class);
        $countries      = $countryService->all($locale);
        $options        = [];
        foreach ($countries as $country) {
            $options[$country['code']] = $country['name'];
        }
        return $options;
    }

    protected function setUp() : void
    {
        parent::setUp();

        $totalColumns = $this->withCountry ? $this->depth + 1 : $this->depth;
        $this->columns($totalColumns);
    }
}
