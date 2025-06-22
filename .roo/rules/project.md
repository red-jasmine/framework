---
description: 
globs: 
alwaysApply: true
---
# 目录结构

- packages 下为每个子领域的 composer 包构建的代码,使用 laravel 的特性
  - src 存放着每个子领域代码
      - Domain 为领域层代码
      - Application 为应用层代码
      - Infrastructure 为基础设施层代码
  - config 领域配置文件
  - routes 领域的默认路由
  - resources 为资源目录 如 lang 翻译文件
  - database 为数据库相关文件, migrations 为数据库迁移文件
- docs 存放着每个子领域的技术文档，同时符合 vitepress 构建文档网站
- tests 存放着每个子领域测试代码


## 公共代码

- packages/support 目录为 提取的公共代码，以及一些标准协议

# DDD 代码类要求

## 领域层
- 领域层代码 一般放在 Domain 下，Models 目录为领域模型，Repositories 目录为仓库接口，Data 目录为模型对应的 DTO

- 领域层模型采用充血模型策略，操作模型内部属性尽量收窄在模型内部
- 
## 应用层
- 应用层 Command 类  一般情况下 继承 领域层 Data 下的基本数据，或者是继承 RedJasmine\Support\Data\Data 类

- 应用层 Query 类，一般情况下 继承 packages\support\src\Domain\Data\Queries 下的查询子类，列表查询 继承 PaginateQuery 类，单个查询继承 FindQuery 类，其他查询继承 Query 类。

- 应用层 后缀 ApplicationService 类一般为 子模块的入口文件，处理器 CommandHandler 和 QueryHandler 需要配置在 $macros 宏中,支持更好的扩展 ,应用层服务类 需要对每个处理器添加注释
```php
/**
 * @see PromoterApplyApprovalCommandHandler::handle()
 * @method approval(PromoterApplyApprovalCommand $command)
 */
 
protected static $macros = [
     'approval' => PromoterApplyApprovalCommandHandler::class,
];

```

## 用户接口层
- Resource 一般继承 RedJasmine\Support\UI\Http\Resources\Json\JsonResource