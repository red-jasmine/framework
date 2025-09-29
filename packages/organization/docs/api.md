# Organization API 文档

## 概述

Organization 包提供了完整的组织管理功能，包括成员、部门、职位等管理功能。

## API 端点

### 成员管理 (Members)

#### 获取成员列表
```
GET /api/v1/organization/members
```

**查询参数：**
- `page`: 页码 (默认: 1)
- `per_page`: 每页数量 (默认: 15)
- `org_id`: 组织ID
- `department_id`: 部门ID
- `mobile`: 手机号
- `include`: 关联数据 (position, departments, leader, subordinates)

**示例：**
```
GET /api/v1/organization/members?include=position,departments&page=1&per_page=20
```

#### 获取单个成员
```
GET /api/v1/organization/members/{id}
```

#### 创建成员
```
POST /api/v1/organization/members
```

**请求体：**
```json
{
    "member_no": "M001",
    "name": "张三",
    "nickname": "小张",
    "mobile": "13800138000",
    "email": "zhangsan@example.com",
    "gender": "male",
    "hired_at": "2024-01-01",
    "status": "active",
    "position_name": "软件工程师",
    "position_level": 5,
    "main_department_id": 1,
    "departments": [1, 2],
    "leader_id": 2
}
```

#### 更新成员
```
PUT /api/v1/organization/members/{id}
```

#### 删除成员
```
DELETE /api/v1/organization/members/{id}
```

### 部门管理 (Departments)

#### 获取部门列表
```
GET /api/v1/organization/departments
```

#### 获取部门树形结构
```
GET /api/v1/organization/departments/tree
```

#### 获取单个部门
```
GET /api/v1/organization/departments/{id}
```

#### 创建部门
```
POST /api/v1/organization/departments
```

**请求体：**
```json
{
    "name": "技术部",
    "code": "TECH",
    "parent_id": null,
    "description": "技术研发部门",
    "sort": 1
}
```

#### 更新部门
```
PUT /api/v1/organization/departments/{id}
```

#### 删除部门
```
DELETE /api/v1/organization/departments/{id}
```

### 职位管理 (Positions)

#### 获取职位列表
```
GET /api/v1/organization/positions
```

#### 获取单个职位
```
GET /api/v1/organization/positions/{id}
```

#### 创建职位
```
POST /api/v1/organization/positions
```

**请求体：**
```json
{
    "name": "高级软件工程师",
    "code": "SENIOR_ENGINEER",
    "level": 5,
    "description": "高级软件工程师职位",
    "sort": 1
}
```

#### 更新职位
```
PUT /api/v1/organization/positions/{id}
```

#### 删除职位
```
DELETE /api/v1/organization/positions/{id}
```

### 组织管理 (Organizations)

#### 获取组织列表
```
GET /api/v1/organization/organizations
```

#### 获取单个组织
```
GET /api/v1/organization/organizations/{id}
```

#### 创建组织
```
POST /api/v1/organization/organizations
```

**请求体：**
```json
{
    "name": "示例公司",
    "code": "EXAMPLE",
    "type": "company",
    "description": "示例公司描述",
    "logo": "https://example.com/logo.png",
    "website": "https://example.com",
    "phone": "400-123-4567",
    "email": "contact@example.com",
    "address": "北京市朝阳区"
}
```

#### 更新组织
```
PUT /api/v1/organization/organizations/{id}
```

#### 删除组织
```
DELETE /api/v1/organization/organizations/{id}
```

## 响应格式

### 成功响应
```json
{
    "data": {
        "id": 1,
        "name": "张三",
        "member_no": "M001",
        "status": "active",
        "status_label": "在职",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### 分页响应
```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7
    }
}
```

### 错误响应
```json
{
    "message": "验证失败",
    "errors": {
        "name": ["姓名不能为空"],
        "email": ["邮箱格式不正确"]
    }
}
```

## 状态码

- `200`: 成功
- `201`: 创建成功
- `400`: 请求错误
- `401`: 未授权
- `403`: 禁止访问
- `404`: 资源不存在
- `422`: 验证失败
- `500`: 服务器错误

## 权限说明

所有 API 都需要用户认证，并且只能访问当前用户所属组织的数据。

## 关联数据

通过 `include` 参数可以加载关联数据：

- `position`: 职位信息
- `departments`: 部门信息
- `leader`: 上级信息
- `subordinates`: 下级信息
- `managed_departments`: 管理的部门

示例：
```
GET /api/v1/organization/members?include=position,departments,leader
```
