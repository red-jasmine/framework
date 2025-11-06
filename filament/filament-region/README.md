# Filament Region

Filament 插件，提供地区管理和国家选择器组件。

## 功能特性

- 国家选择器表单组件
- Region 资源管理界面

## 安装

```bash
composer require red-jasmine/filament-region
```

## 使用

### 注册插件

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

### 使用国家选择器

```php
use RedJasmine\FilamentRegion\Forms\Components\CountrySelect;

CountrySelect::make('country_code')
    ->label('国家')
    ->required()
```

## License

MIT

