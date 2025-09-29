# Organization UI 层

## 概述

Organization UI 层提供了完整的组织管理用户界面，包括成员、部门、职位等管理功能。

## 目录结构

```
src/UI/
├── Http/
│   └── Owner/                    # 所有者端接口
│       ├── Api/                 # API 接口
│       │   ├── Controllers/     # 控制器
│       │   ├── Resources/       # API 资源
│       │   └── Requests/        # 请求验证
│       └── OrganizationOwnerRoute.php  # 路由定义
├── OrganizationUIServiceProvider.php   # 服务提供者
└── README.md                     # 说明文档
```

## 功能模块

### 1. 成员管理 (Member)

**控制器：** `MemberController`
**资源：** `MemberResource`
**请求验证：** `MemberCreateRequest`, `MemberUpdateRequest`

**功能：**
- 成员列表查询（支持分页、过滤、排序）
- 成员详情查看
- 成员创建
- 成员信息更新
- 成员删除

**API 端点：**
- `GET /api/v1/organization/members` - 获取成员列表
- `GET /api/v1/organization/members/{id}` - 获取成员详情
- `POST /api/v1/organization/members` - 创建成员
- `PUT /api/v1/organization/members/{id}` - 更新成员
- `DELETE /api/v1/organization/members/{id}` - 删除成员

### 2. 部门管理 (Department)

**控制器：** `DepartmentController`
**资源：** `DepartmentResource`

**功能：**
- 部门列表查询
- 部门树形结构展示
- 部门详情查看
- 部门创建
- 部门信息更新
- 部门删除

**API 端点：**
- `GET /api/v1/organization/departments` - 获取部门列表
- `GET /api/v1/organization/departments/tree` - 获取部门树
- `GET /api/v1/organization/departments/{id}` - 获取部门详情
- `POST /api/v1/organization/departments` - 创建部门
- `PUT /api/v1/organization/departments/{id}` - 更新部门
- `DELETE /api/v1/organization/departments/{id}` - 删除部门

### 3. 职位管理 (Position)

**控制器：** `PositionController`
**资源：** `PositionResource`

**功能：**
- 职位列表查询
- 职位详情查看
- 职位创建
- 职位信息更新
- 职位删除

**API 端点：**
- `GET /api/v1/organization/positions` - 获取职位列表
- `GET /api/v1/organization/positions/{id}` - 获取职位详情
- `POST /api/v1/organization/positions` - 创建职位
- `PUT /api/v1/organization/positions/{id}` - 更新职位
- `DELETE /api/v1/organization/positions/{id}` - 删除职位

### 4. 组织管理 (Organization)

**控制器：** `OrganizationController`
**资源：** `OrganizationResource`

**功能：**
- 组织列表查询
- 组织详情查看
- 组织创建
- 组织信息更新
- 组织删除

**API 端点：**
- `GET /api/v1/organization/organizations` - 获取组织列表
- `GET /api/v1/organization/organizations/{id}` - 获取组织详情
- `POST /api/v1/organization/organizations` - 创建组织
- `PUT /api/v1/organization/organizations/{id}` - 更新组织
- `DELETE /api/v1/organization/organizations/{id}` - 删除组织

### 5. 部门管理员管理 (DepartmentManager)

**控制器：** `DepartmentManagerController`
**资源：** `DepartmentManagerResource`

**功能：**
- 部门管理员列表查询
- 部门管理员详情查看
- 部门管理员创建
- 部门管理员信息更新
- 部门管理员删除

### 6. 成员部门关系管理 (MemberDepartment)

**控制器：** `MemberDepartmentController`
**资源：** `MemberDepartmentResource`

**功能：**
- 成员部门关系列表查询
- 成员部门关系详情查看
- 成员部门关系创建
- 成员部门关系信息更新
- 成员部门关系删除

## 技术特性

### 1. RESTful API 设计
- 遵循 REST 设计原则
- 统一的响应格式
- 标准的 HTTP 状态码

### 2. 数据验证
- 请求数据验证
- 业务规则验证
- 错误信息本地化

### 3. 权限控制
- 基于所有者的数据隔离
- 自动注入当前用户信息
- 查询作用域限制

### 4. 关联数据加载
- 支持关联数据预加载
- 避免 N+1 查询问题
- 灵活的关联数据控制

### 5. 分页和过滤
- 支持分页查询
- 支持字段过滤
- 支持排序功能

## 使用说明

### 1. 注册服务提供者

在 `config/app.php` 中注册服务提供者：

```php
'providers' => [
    // ...
    RedJasmine\Organization\UI\OrganizationUIServiceProvider::class,
],
```

### 2. 发布语言文件

```bash
php artisan vendor:publish --tag=organization-translations
```

### 3. 配置路由

路由会自动注册，也可以通过 `OrganizationOwnerRoute` 类手动注册：

```php
use RedJasmine\Organization\UI\Http\Owner\OrganizationOwnerRoute;

// API 路由
OrganizationOwnerRoute::api();

// Web 路由
OrganizationOwnerRoute::web();
```

### 4. 权限配置

确保用户认证中间件已配置，并且用户模型实现了 `UserInterface` 接口。

## 扩展说明

### 1. 自定义控制器

可以继承基础控制器并添加自定义方法：

```php
class CustomMemberController extends MemberController
{
    public function customAction(Request $request)
    {
        // 自定义逻辑
    }
}
```

### 2. 自定义资源

可以继承基础资源类并自定义响应格式：

```php
class CustomMemberResource extends MemberResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);
        
        // 添加自定义字段
        $data['custom_field'] = $this->custom_field;
        
        return $data;
    }
}
```

### 3. 自定义验证

可以创建自定义请求验证类：

```php
class CustomMemberCreateRequest extends MemberCreateRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        
        // 添加自定义验证规则
        $rules['custom_field'] = ['required', 'string'];
        
        return $rules;
    }
}
```

## 注意事项

1. 所有 API 都需要用户认证
2. 数据访问受所有者权限控制
3. 关联数据加载需要明确指定
4. 分页参数有默认值限制
5. 删除操作需要谨慎处理关联数据
