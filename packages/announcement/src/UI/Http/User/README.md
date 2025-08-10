# Announcement User UI Layer

This directory contains the User interface layer for the announcement package, providing read-only access to published announcements and visible categories.

## API Endpoints

### Announcements (User Access)

- `GET /api/user/announcement/announcements` - 获取已发布的公告列表
- `GET /api/user/announcement/announcements/{id}` - 获取公告详情

### Categories (User Access)

- `GET /api/user/announcement/categories` - 获取显示的分类列表
- `GET /api/user/announcement/categories/{id}` - 获取分类详情
- `GET /api/user/announcement/categories/tree` - 获取分类树结构

## Controllers

### AnnouncementController
- **功能**: 用户端公告查看
- **权限**: 仅允许查看已发布的公告 (status = published)
- **过滤**: 自动过滤未发布的公告

### CategoryController
- **功能**: 用户端分类查看
- **权限**: 仅允许查看显示的分类 (is_show = true)
- **过滤**: 自动过滤隐藏的分类

## Resources

### AnnouncementResource
- 隐敏感信息（如所属者信息、创建者信息等）
- 仅返回用户需要的基本字段

### CategoryResource
- 隐藏管理相关字段
- 仅返回分类的基本展示信息

## 特点

1. **只读访问**: 用户只能查看，不能创建、更新或删除
2. **数据过滤**: 自动过滤未发布的公告和隐藏的分类
3. **信息保护**: 隐藏敏感的管理信息
4. **权限控制**: 基于用户身份的访问控制

## 使用示例

```bash
# 获取公告列表
curl -X GET /api/user/announcement/announcements \
  -H "Authorization: Bearer {user_token}"

# 获取分类树
curl -X GET /api/user/announcement/categories/tree \
  -H "Authorization: Bearer {user_token}"
```
