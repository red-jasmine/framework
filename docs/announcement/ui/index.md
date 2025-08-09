# 公告领域用户接口层设计

## 1. 控制器

### 1.1 公告控制器 (AnnouncementController)

#### 功能描述
公告控制器处理公告相关的HTTP请求。

#### 核心接口
- POST /announcements - 创建公告
- PUT /announcements/{id} - 更新公告
- DELETE /announcements/{id} - 删除公告
- POST /announcements/{id}/publish - 发布公告
- POST /announcements/{id}/revoke - 撤销公告
- POST /announcements/{id}/submit-approval - 提交审批
- POST /announcements/{id}/approve - 审批通过
- POST /announcements/{id}/reject - 审批拒绝
- GET /announcements/{id} - 获取公告详情
- GET /announcements - 分页获取公告列表

### 1.2 分类控制器 (CategoryController)

#### 功能描述
分类控制器处理公告分类相关的HTTP请求。

#### 核心接口
- POST /announcement-categories - 创建分类
- PUT /announcement-categories/{id} - 更新分类
- DELETE /announcement-categories/{id} - 删除分类
- POST /announcement-categories/{id}/show - 显示分类
- POST /announcement-categories/{id}/hide - 隐藏分类
- POST /announcement-categories/{id}/move - 移动分类
- GET /announcement-categories/{id} - 获取分类详情
- GET /announcement-categories - 分页获取分类列表
- GET /announcement-categories/tree - 获取分类树

## 2. API资源

### 2.1 公告资源 (AnnouncementResource)

#### 功能描述
公告资源负责将公告模型转换为API响应格式。

#### 核心字段
- id: 公告ID
- biz: 业务线
- owner: 所有者信息
- category: 分类信息
- title: 公告标题
- cover: 公告封面
- content: 公告内容
- scopes: 可见范围
- channels: 发布渠道
- publish_time: 发布时间
- status: 公告状态
- attachments: 附件信息
- approval_status: 审批状态
- is_force_read: 是否强制阅读
- created_at: 创建时间
- updated_at: 更新时间

### 2.2 分类资源 (CategoryResource)

#### 功能描述
分类资源负责将分类模型转换为API响应格式。

#### 核心字段
- id: 分类ID
- biz: 业务线
- owner: 所有者信息
- parent: 父级分类信息
- name: 分类名称
- description: 分类描述
- sort: 排序
- is_show: 是否显示
- created_at: 创建时间
- updated_at: 更新时间

## 3. 请求验证

### 3.1 公告请求验证

#### CreateAnnouncementRequest
创建公告请求验证规则：
- title: 必填，长度不超过255字符
- content: 必填，JSON格式
- category_id: 可选，存在性验证
- scopes: 可选，数组格式
- channels: 可选，数组格式

#### UpdateAnnouncementRequest
更新公告请求验证规则：
- title: 可选，长度不超过255字符
- content: 可选，JSON格式
- category_id: 可选，存在性验证
- scopes: 可选，数组格式
- channels: 可选，数组格式

### 3.2 分类请求验证

#### CreateCategoryRequest
创建分类请求验证规则：
- name: 必填，长度不超过100字符
- parent_id: 可选，存在性验证
- description: 可选，长度不超过255字符

#### UpdateCategoryRequest
更新分类请求验证规则：
- name: 可选，长度不超过100字符
- parent_id: 可选，存在性验证
- description: 可选，长度不超过255字符

## 4. 路由定义

### 4.1 管理端路由

```php
// 公告管理路由
Route::prefix('admin/announcements')->group(function () {
    Route::post('/', [AnnouncementController::class, 'store']); // 创建公告
    Route::put('/{id}', [AnnouncementController::class, 'update']); // 更新公告
    Route::delete('/{id}', [AnnouncementController::class, 'destroy']); // 删除公告
    Route::post('/{id}/publish', [AnnouncementController::class, 'publish']); // 发布公告
    Route::post('/{id}/revoke', [AnnouncementController::class, 'revoke']); // 撤销公告
    Route::post('/{id}/submit-approval', [AnnouncementController::class, 'submitForApproval']); // 提交审批
    Route::post('/{id}/approve', [AnnouncementController::class, 'approve']); // 审批通过
    Route::post('/{id}/reject', [AnnouncementController::class, 'reject']); // 审批拒绝
    Route::get('/{id}', [AnnouncementController::class, 'show']); // 获取公告详情
    Route::get('/', [AnnouncementController::class, 'index']); // 获取公告列表
});

// 公告分类管理路由
Route::prefix('admin/announcement-categories')->group(function () {
    Route::post('/', [CategoryController::class, 'store']); // 创建分类
    Route::put('/{id}', [CategoryController::class, 'update']); // 更新分类
    Route::delete('/{id}', [CategoryController::class, 'destroy']); // 删除分类
    Route::post('/{id}/show', [CategoryController::class, 'showCategory']); // 显示分类
    Route::post('/{id}/hide', [CategoryController::class, 'hideCategory']); // 隐藏分类
    Route::post('/{id}/move', [CategoryController::class, 'move']); // 移动分类
    Route::get('/{id}', [CategoryController::class, 'show']); // 获取分类详情
    Route::get('/', [CategoryController::class, 'index']); // 获取分类列表
    Route::get('/tree', [CategoryController::class, 'tree']); // 获取分类树
});
```

### 4.2 用户端路由

```php
// 用户端公告路由
Route::prefix('user/announcements')->group(function () {
    Route::get('/{id}', [UserAnnouncementController::class, 'show']); // 获取公告详情
    Route::get('/', [UserAnnouncementController::class, 'index']); // 获取公告列表
});

// 用户端公告分类路由
Route::prefix('user/announcement-categories')->group(function () {
    Route::get('/{id}', [UserCategoryController::class, 'show']); // 获取分类详情
    Route::get('/', [UserCategoryController::class, 'index']); // 获取分类列表
    Route::get('/tree', [UserCategoryController::class, 'tree']); // 获取分类树
});