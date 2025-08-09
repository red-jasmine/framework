# 公告领域基础设施层设计

## 1. 仓库实现

### 1.1 公告写操作仓库 (AnnouncementRepository)

#### 功能描述
公告写操作仓库负责公告实体的持久化操作。

#### 核心方法
- save(): 保存公告
- remove(): 删除公告
- findById(): 根据ID查找公告
- findByBizAndOwner(): 根据业务线和所有者查找公告

### 1.2 公告只读仓库 (AnnouncementReadRepository)

#### 功能描述
公告只读仓库负责公告数据的查询操作。

#### 核心方法
- find(): 查询公告详情
- paginate(): 分页查询公告列表
- findByStatus(): 根据状态查询公告
- findByApprovalStatus(): 根据审批状态查询公告

### 1.3 分类写操作仓库 (CategoryRepository)

#### 功能描述
分类写操作仓库负责分类实体的持久化操作。

#### 核心方法
- save(): 保存分类
- remove(): 删除分类
- findById(): 根据ID查找分类
- findByBizAndOwner(): 根据业务线和所有者查找分类

### 1.4 分类只读仓库 (CategoryReadRepository)

#### 功能描述
分类只读仓库负责分类数据的查询操作。

#### 核心方法
- find(): 查询分类详情
- paginate(): 分页查询分类列表
- tree(): 查询分类树
- findByParent(): 根据父级分类查询子分类

## 2. 过滤器配置

### 2.1 公告过滤器

#### 允许的过滤字段
- title: 公告标题（模糊匹配）
- status: 公告状态
- approval_status: 审批状态
- category_id: 分类ID
- biz: 业务线
- owner_type: 所有者类型
- owner_id: 所有者ID

#### 允许的排序字段
- created_at: 创建时间
- updated_at: 更新时间
- publish_time: 发布时间

#### 允许的关联包含
- category: 关联分类信息

### 2.2 分类过滤器

#### 允许的过滤字段
- name: 分类名称（模糊匹配）
- is_show: 是否显示
- parent_id: 父级分类ID
- biz: 业务线
- owner_type: 所有者类型
- owner_id: 所有者ID

#### 允许的排序字段
- sort: 排序
- created_at: 创建时间

#### 允许的关联包含
- parent: 父级分类信息