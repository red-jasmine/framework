# 公告领域应用层设计

## 1. 应用服务

### 1.1 公告应用服务 (AnnouncementAppService)

#### 功能描述
公告应用服务负责处理公告相关的业务用例，协调领域层和基础设施层完成业务操作。

#### 核心方法
- create(): 创建公告
- update(): 更新公告
- delete(): 删除公告
- publish(): 发布公告
- revoke(): 撤销公告
- submitForApproval(): 提交审批
- approve(): 审批通过
- reject(): 审批拒绝
- find(): 查询公告详情
- paginate(): 分页查询公告列表

### 1.2 分类应用服务 (CategoryAppService)

#### 功能描述
分类应用服务负责处理公告分类相关的业务用例。

#### 核心方法
- create(): 创建分类
- update(): 更新分类
- delete(): 删除分类
- show(): 显示分类
- hide(): 隐藏分类
- move(): 移动分类
- find(): 查询分类详情
- paginate(): 分页查询分类列表
- tree(): 查询分类树

## 2. 命令设计

### 2.1 基础命令

#### CreateAnnouncementCommand
创建公告命令

#### UpdateAnnouncementCommand
更新公告命令

#### DeleteAnnouncementCommand
删除公告命令

#### CreateCategoryCommand
创建分类命令

#### UpdateCategoryCommand
更新分类命令

#### DeleteCategoryCommand
删除分类命令

### 2.2 业务命令

#### PublishAnnouncementCommand
发布公告命令

#### RevokeAnnouncementCommand
撤销公告命令

#### SubmitAnnouncementForApprovalCommand
提交公告审批命令

#### ApproveAnnouncementCommand
审批通过公告命令

#### RejectAnnouncementCommand
拒绝公告命令

## 3. 查询设计

### 3.1 基础查询

#### FindAnnouncementQuery
查询公告详情

#### PaginateAnnouncementsQuery
分页查询公告列表

#### FindCategoryQuery
查询分类详情

#### PaginateCategoriesQuery
分页查询分类列表

### 3.2 业务查询

#### TreeCategoriesQuery
查询分类树

#### PublishedAnnouncementsQuery
查询已发布公告

#### PendingApprovalAnnouncementsQuery
查询待审批公告

## 4. 命令处理器

### 4.1 公告命令处理器

#### CreateAnnouncementCommandHandler
处理创建公告命令

#### UpdateAnnouncementCommandHandler
处理更新公告命令

#### DeleteAnnouncementCommandHandler
处理删除公告命令

#### PublishAnnouncementCommandHandler
处理发布公告命令

#### RevokeAnnouncementCommandHandler
处理撤销公告命令

#### SubmitAnnouncementForApprovalCommandHandler
处理提交审批命令

#### ApproveAnnouncementCommandHandler
处理审批通过命令

#### RejectAnnouncementCommandHandler
处理审批拒绝命令

### 4.2 分类命令处理器

#### CreateCategoryCommandHandler
处理创建分类命令

#### UpdateCategoryCommandHandler
处理更新分类命令

#### DeleteCategoryCommandHandler
处理删除分类命令

## 5. 查询处理器

### 5.1 公告查询处理器

#### FindAnnouncementQueryHandler
处理查询公告详情

#### PaginateAnnouncementsQueryHandler
处理分页查询公告列表

### 5.2 分类查询处理器

#### FindCategoryQueryHandler
处理查询分类详情

#### PaginateCategoriesQueryHandler
处理分页查询分类列表

#### TreeCategoriesQueryHandler
处理查询分类树

## 6. 转换器

### 6.1 公告转换器
- AnnouncementDataToModelConverter: 将公告数据转换为公告模型
- AnnouncementModelToDataConverter: 将公告模型转换为公告数据

### 6.2 分类转换器
- CategoryDataToModelConverter: 将分类数据转换为分类模型
- CategoryModelToDataConverter: 将分类模型转换为分类数据