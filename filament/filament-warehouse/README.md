# Red Jasmine Filament Warehouse Package

## 概述

Filament 管理端 - 仓库管理模块，提供仓库的完整管理界面。

## 功能特性

- **仓库管理**: 仓库的创建、编辑、删除、列表查询
- **仓库类型**: 支持仓库、门店、配送中心等多种类型
- **所有者管理**: 支持按所有者过滤和管理仓库
- **状态管理**: 支持启用/禁用、默认仓库设置

## 安装

```bash
composer require red-jasmine/filament-warehouse
```

## 使用

在 Filament Panel 中注册插件：

```php
use RedJasmine\FilamentWarehouse\FilamentWarehousePlugin;

$panel->plugins([
    FilamentWarehousePlugin::make(),
]);
```

## 许可证

MIT

