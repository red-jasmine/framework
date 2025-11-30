# 商品媒体资源表设计方案

## 一、方案概述

### 1.1 设计目标

设计一个统一的商品媒体资源表，用于存储商品和变体的图片、视频等媒体资源，实现：
- ✅ 商品级别的媒体资源管理
- ✅ 变体（SKU）级别的媒体资源管理
- ✅ 主图标记和排序功能
- ✅ 媒体类型扩展（图片、视频、文档、3D模型等）
- ✅ 媒体元数据存储（尺寸、文件大小、MIME类型等）

### 1.2 设计原则

- **单表设计**：使用一张表统一管理所有媒体资源，简化查询和维护
- **灵活关联**：通过 `product_id` 和 `variant_id` 字段灵活关联商品和变体
- **性能优化**：通过合理的索引设计提升查询性能
- **扩展性强**：预留扩展字段，支持未来功能扩展

## 二、表结构设计

### 2.1 表名

`product_media` - 商品媒体资源表

### 2.2 字段设计

#### 主键和关联字段

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `id` | `unsignedBigInteger` | 主键ID | 使用雪花ID |
| `product_id` | `unsignedBigInteger` | 商品ID | 必填，关联商品表 |
| `variant_id` | `unsignedBigInteger` | 变体ID（SKU ID） | 可选，关联变体表 |

#### 媒体类型字段

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `media_type` | `string(32)` | 媒体类型 | image\|video\|document\|3d_model |
| `mime_type` | `string(100)` | MIME类型 | 如 image/jpeg, video/mp4 |

#### 文件信息字段

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `url` | `string` | 媒体URL | CDN地址或相对路径 |
| `file_name` | `string` | 文件名 | 原始文件名 |
| `file_size` | `unsignedBigInteger` | 文件大小 | 单位：字节 |

#### 图片属性字段（仅图片类型有效）

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `width` | `unsignedInteger` | 宽度 | 单位：像素 |
| `height` | `unsignedInteger` | 高度 | 单位：像素 |
| `alt_text` | `string(500)` | 替代文本 | 用于无障碍访问和SEO |

#### 排序和标记字段

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `position` | `integer` | 排序位置 | 数字越小越靠前，默认0 |
| `is_primary` | `boolean` | 是否主图 | 商品或变体的主图标记（主图即封面图） |
| `is_enabled` | `boolean` | 是否启用 | 控制媒体是否显示 |

#### 扩展字段

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `extra` | `json` | 扩展字段 | 存储额外的媒体信息 |

#### 系统字段

| 字段名 | 类型 | 说明 | 备注 |
|--------|------|------|------|
| `version` | `unsignedBigInteger` | 版本号 | 乐观锁 |
| `creator_type` | `string(64)` | 创建者类型 | operator() 宏 |
| `creator_id` | `string(64)` | 创建者ID | operator() 宏 |
| `creator_nickname` | `string(255)` | 创建者昵称 | operator() 宏 |
| `updater_type` | `string(64)` | 更新者类型 | operator() 宏 |
| `updater_id` | `string(64)` | 更新者ID | operator() 宏 |
| `updater_nickname` | `string(255)` | 更新者昵称 | operator() 宏 |
| `created_at` | `timestamp` | 创建时间 | timestamps() |
| `updated_at` | `timestamp` | 更新时间 | timestamps() |
| `deleted_at` | `timestamp` | 删除时间 | softDeletes() |

### 2.3 索引设计

| 索引名 | 字段 | 说明 |
|--------|------|------|
| `idx_product` | `product_id` | 商品查询 |
| `idx_variant` | `variant_id` | 变体查询 |
| `idx_product_variant` | `product_id, variant_id` | 商品+变体联合查询 |
| `idx_product_type` | `product_id, media_type` | 按类型查询商品媒体 |
| `idx_product_primary` | `product_id, is_primary` | 查询商品主图 |
| `idx_variant_primary` | `variant_id, is_primary` | 查询变体主图 |
| `idx_product_position` | `product_id, position` | 按位置排序查询 |

## 三、使用场景

### 3.1 商品级别媒体

**场景**：商品的主图和轮播图

**数据示例**：
- `product_id = 123`, `variant_id = NULL`, `media_type = 'image'`, `is_primary = true`, `position = 1`
- `product_id = 123`, `variant_id = NULL`, `media_type = 'image'`, `is_primary = false`, `position = 2`
- `product_id = 123`, `variant_id = NULL`, `media_type = 'video'`, `is_primary = false`, `position = 1`

**查询示例**：
```sql
-- 获取商品主图
SELECT * FROM product_media 
WHERE product_id = 123 
  AND variant_id IS NULL 
  AND is_primary = true 
  AND deleted_at IS NULL;

-- 获取商品所有图片（按位置排序）
SELECT * FROM product_media 
WHERE product_id = 123 
  AND variant_id IS NULL 
  AND media_type = 'image' 
  AND deleted_at IS NULL 
ORDER BY position ASC;
```

### 3.2 变体级别媒体

**场景**：不同颜色/尺寸的变体有不同图片

**数据示例**：
- `product_id = 123`, `variant_id = 456`, `media_type = 'image'`, `is_primary = true`, `position = 1`
- `product_id = 123`, `variant_id = 456`, `media_type = 'image'`, `is_primary = false`, `position = 2`
- `product_id = 123`, `variant_id = 789`, `media_type = 'image'`, `is_primary = true`, `position = 1`

**查询示例**：
```sql
-- 获取变体主图
SELECT * FROM product_media 
WHERE variant_id = 456 
  AND is_primary = true 
  AND deleted_at IS NULL;

-- 获取变体所有图片
SELECT * FROM product_media 
WHERE variant_id = 456 
  AND media_type = 'image' 
  AND deleted_at IS NULL 
ORDER BY position ASC;
```

### 3.3 主图回退机制

**场景**：变体没有图片时，使用商品主图

**逻辑**：
1. 优先查询变体的主图（`variant_id = X AND is_primary = true`）
2. 如果没有，查询商品的主图（`product_id = X AND variant_id IS NULL AND is_primary = true`）

**查询示例**：
```sql
-- 获取变体图片（带回退）
SELECT * FROM product_media 
WHERE (
    (variant_id = 456 AND is_primary = true)
    OR 
    (variant_id IS NULL AND product_id = 123 AND is_primary = true)
)
AND deleted_at IS NULL 
ORDER BY variant_id DESC 
LIMIT 1;
```

## 四、业务规则

### 4.1 主图规则

1. **商品主图**：
   - 每个商品最多只能有一个主图（`product_id = X AND variant_id IS NULL AND is_primary = true`）
   - 设置新主图时，自动取消旧主图标记

2. **变体主图**：
   - 每个变体最多只能有一个主图（`variant_id = X AND is_primary = true`）
   - 变体主图与商品主图独立，互不影响

3. **主图回退**：
   - 如果变体没有主图，前端显示商品主图
   - 如果商品没有主图，显示第一张图片（`position` 最小）

### 4.2 排序规则

1. **位置排序**：
   - `position` 字段控制显示顺序
   - 数字越小越靠前
   - 相同 `position` 时，按创建时间排序

2. **自动排序**：
   - 新增媒体时，如果没有指定 `position`，自动设置为最大值+1
   - 删除媒体时，不影响其他媒体的排序

### 4.3 媒体类型规则

1. **图片类型**（`media_type = 'image'`）：
   - 必须填写 `width`、`height`、`mime_type`
   - 建议填写 `alt_text`（SEO和无障碍访问）

2. **视频类型**（`media_type = 'video'`）：
   - 必须填写 `mime_type`、`file_size`
   - `width`、`height` 可选（用于视频封面）

3. **文档类型**（`media_type = 'document'`）：
   - 必须填写 `mime_type`、`file_size`
   - 用于商品说明书、证书等

4. **3D模型**（`media_type = '3d_model'`）：
   - 必须填写 `mime_type`
   - 用于3D展示功能

### 4.4 软删除规则

1. **软删除**：
   - 删除媒体时使用软删除，保留历史数据
   - 查询时自动过滤已删除的记录

2. **级联删除**：
   - 删除商品时，不级联删除媒体（保留历史）
   - 删除变体时，不级联删除媒体（保留历史）

## 五、与现有字段的关系

### 5.1 商品表 `image` 字段

**现状**：`products` 表有 `image` 字段存储主图URL

**处理方案**：
1. **方案A（推荐）**：保留 `image` 字段作为冗余
   - 优点：查询性能好，代码改动小
   - 缺点：需要同步维护
   - 实现：通过模型事件同步 `product_media` 表的主图到 `products.image`

2. **方案B**：去除 `image` 字段
   - 优点：数据规范化，无冗余
   - 缺点：查询需要 JOIN，代码改动大
   - 实现：通过访问器方法从 `product_media` 表获取主图

### 5.2 变体表 `image` 字段

**现状**：`product_variants` 表有 `image` 字段存储主图URL

**处理方案**：
- **推荐保留**：变体图片字段作为冗余
- **原因**：变体图片查询频率高，保留字段提升性能
- **实现**：通过模型事件同步 `product_media` 表的主图到 `product_variants.image`

### 5.3 扩展表 `images` 和 `videos` JSON字段

**现状**：`products_extension` 表有 `images` 和 `videos` JSON字段

**处理方案**：
- **逐步替换**：新数据写入 `product_media` 表
- **过渡期**：保留 JSON 字段，支持向后兼容
- **最终**：完全替换后，可以保留 JSON 字段作为缓存或去除

## 六、查询性能优化

### 6.1 常用查询优化

1. **商品主图查询**：
   - 使用 `idx_product_primary` 索引
   - 查询条件：`product_id = X AND is_primary = true`

2. **商品图片列表查询**：
   - 使用 `idx_product_position` 索引
   - 查询条件：`product_id = X AND media_type = 'image' ORDER BY position`

3. **变体主图查询**：
   - 使用 `idx_variant_primary` 索引
   - 查询条件：`variant_id = X AND is_primary = true`

### 6.2 批量查询优化

1. **商品列表带主图**：
   - 方案A：使用 `products.image` 字段（推荐，性能最好）
   - 方案B：使用 `LEFT JOIN product_media` 查询主图

2. **变体列表带主图**：
   - 方案A：使用 `product_variants.image` 字段（推荐，性能最好）
   - 方案B：使用 `LEFT JOIN product_media` 查询主图

## 七、扩展性考虑

### 7.1 媒体类型扩展

未来可以支持的媒体类型：
- `image` - 图片（已支持）
- `video` - 视频（已支持）
- `document` - 文档（已支持）
- `3d_model` - 3D模型（已支持）
- `audio` - 音频（可扩展）
- `ar_model` - AR模型（可扩展）

### 7.2 扩展字段使用

`extra` JSON字段可以存储：
- 图片处理信息（缩略图URL、水印信息等）
- 视频信息（时长、分辨率、码率等）
- 3D模型信息（格式、版本、预览图等）
- CDN信息（CDN类型、区域等）
- 审核信息（审核状态、审核时间等）

## 八、总结

### 9.1 设计优势

1. **统一管理**：所有媒体资源在一张表中，便于管理和查询
2. **灵活关联**：支持商品级别和变体级别的媒体资源
3. **性能优化**：合理的索引设计，支持高效查询
4. **扩展性强**：预留扩展字段，支持未来功能扩展
5. **符合规范**：遵循项目数据库设计规范

### 8.2 注意事项

1. **主图同步**：需要保证 `products.image` 和 `product_media` 表的主图同步
2. **变体图片**：建议保留 `product_variants.image` 字段作为冗余
3. **查询优化**：列表查询时优先使用冗余字段，详情查询时使用关联查询

