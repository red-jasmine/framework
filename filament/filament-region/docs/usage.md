# Filament Region 使用说明

## 安装

1. 在 `composer.json` 中添加依赖：

```json
{
    "require": {
        "red-jasmine/filament-region": "1.0.x-dev"
    }
}
```

2. 运行 `composer update`

## 注册插件

在 Panel Provider 中注册插件：

```php
use RedJasmine\FilamentRegion\FilamentRegionPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentRegionPlugin::make(),
        ]);
}
```

## 功能特性

### 1. 国家选择器表单组件

在任何 Filament 表单中使用国家选择器：

#### 基础用法

```php
use RedJasmine\FilamentRegion\Forms\Components\CountrySelect;

CountrySelect::make('country_code')
    ->label('国家')
    ->required()
```

#### 默认选中中国

```php
CountrySelect::make('country_code')
    ->label('国家')
    ->defaultChina()
```

#### 显示国旗图标

```php
CountrySelect::make('country_code')
    ->label('国家')
    ->withFlag()  // 在选项前显示国旗 Emoji
```

#### 完整示例

```php
use RedJasmine\FilamentRegion\Forms\Components\CountrySelect;
use Filament\Forms\Components\TextInput;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->label('名称')
                ->required(),
                
            CountrySelect::make('country_code')
                ->label('国家')
                ->defaultChina()
                ->withFlag()
                ->required(),
        ]);
}
```

### 2. Region 资源管理

插件自动注册了 Region 资源管理界面，提供以下功能：

- 树形结构展示行政区划
- 创建/编辑/删除行政区划
- 支持多级联动（省/市/区/街道等）
- 国家代码关联
- 电话区号管理
- 区域类型管理

#### 访问资源

注册插件后，在 Filament 面板中会自动出现"地区管理"菜单，包含：

- **行政区划**: 管理省市区等行政区划数据

#### 字段说明

- **代码** (code): 唯一标识，如 "110000", "110100" 等
- **上级区划** (parent_code): 父级行政区划
- **国家** (country_code): ISO 3166-1 alpha-2 国家代码
- **类型** (type): 行政区划类型（省/市/区/街道/村庄）
- **名称** (name): 行政区划名称
- **大区** (region): 所属大区
- **层级** (level): 树形层级，自动计算

## 数据结构

### Region 模型

```php
namespace RedJasmine\Region\Domain\Models;

class Region extends Model
{
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // 关系
    public function parent(); // 父级区划
    public function children(); // 子级区划
}
```

### RegionTypeEnum 枚举

- `COUNTRY`: 国家
- `PROVINCE`: 省
- `CITY`: 城市
- `DISTRICT`: 县区
- `STREET`: 街道乡镇
- `VILLAGE`: 村庄社区

## API 调用

如果需要在代码中使用：

```php
use RedJasmine\Region\Application\Services\Country\CountryService;
use RedJasmine\Region\Application\Services\Region\RegionApplicationService;

// 获取所有国家
$countryService = app(CountryService::class);
$countries = $countryService->all('zh_CN');

// 查询区划
$regionService = app(RegionApplicationService::class);
$regions = $regionService->paginate($query);
```

## 自定义

### 自定义翻译

复制语言文件到项目中进行自定义：

```bash
php artisan vendor:publish --tag="red-jasmine-filament-region-translations"
```

### 自定义配置

发布配置文件：

```bash
php artisan vendor:publish --tag="red-jasmine-filament-region-config"
```

## 注意事项

1. Region 模型使用 `code` 作为主键，而不是自增 ID
2. 国家选择器基于 Symfony Intl 组件，支持 ISO 3166-1 标准
3. 区划数据需要自行导入或通过管理界面维护
4. 支持树形结构，理论上支持无限级

