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
- 领域层代码 一般放在 Domain 下，Models 目录为领域模型
  - Models 目录为领域模型
  - Models/Enums 为模型属性的枚举目录
  - Models/ValueObjects 为模型属性对应的值对象
  - Repositories 目录为仓库接口
  - Data 目录为模型对应的基础 DTO
  - Transformers 为转换器目录
  - Events 为领域事件

- 领域层模型采用充血模型策略，操作模型内部属性尽量收窄在模型内部
- 在 Transformers 目录创建模型对应的转换器，主要对模型和基础 DTO 进行转换，转换器一般 需要时间接口 RedJasmine\Support\Domain\Transformer\TransformerInterface

## 应用层
- 应用层 Command 类  一般情况下 继承 领域层 Data 下的基本数据，或者是继承 RedJasmine\Support\Data\Data 类

- 应用层 Query 类，一般情况下 继承 packages\support\src\Domain\Data\Queries 下的查询子类，列表查询 继承 PaginateQuery 类，单个查询继承 FindQuery 类，其他查询继承 Query 类。

###  应用层 XXXApplicationService

> 应用层 后缀 ApplicationService 类一般为 子模块的入口文件，

要求
- 子模块的xxApplicationService  一般情况下 继承   RedJasmine\Support\Application\ApplicationService
- 如果子模块功能只有简单的增删改查，ApplicationService 基类功能已经内置,不需要而外的指令和查询以及处理器
- 处理器 CommandHandler 和 QueryHandler 需要配置在 $macros 宏中,支持更好的扩展 。
- 应用层服务类 需要对每个处理器添加注释
- 构建方法需要注入 仓库接口、只读仓库接口，模型转换器

```php
/**
 * @see PromoterApplyApprovalCommandHandler::handle()
 * @method approval(PromoterApplyApprovalCommand $command)
 */
protected static $macros = [
     'approval' => PromoterApplyApprovalCommandHandler::class,
];

```
###  处理器 CommandHandler、QueryHandler

- 处理器 一般注入protected XXXApplicationService $service, 在处理器中 使用 service 中的 注入的仓库实现

## 用户接口层 UI
- Resource 一般继承 RedJasmine\Support\UI\Http\Resources\Json\JsonResource
- Http 下 为 每个角色的 Http 服务，一般角色有 Admin、User、Shop
- 每个角色下 有 Api、Web 等入口
- 角色下有一个文件 {模块}{角色}Route 文件作为，所有路由的管理器。如: PromoterUserRoute
```php
/**
 * api 理由
 */
public static function api() : void
{
      Route::apiResource('promoters/applies', PromoterApplyController::class)->names('distribution.api.user.promoter-applies')->only(['index', 'show']);

}
/**
 * Web 路由
 */
public static function web() : void
{
  
}
```


## 综合规则
- 如果 Model  继承了 RedJasmine\Support\Domain\Models\BaseCategoryModel ,则 转换器、应用层服务等 基本上是继承 公共代码中的基础类。