# 商品领域多语言体系设计方案

## 文档信息

| 项目 | 内容 |
|------|------|
| **方案名称** | 商品领域多语言体系设计方案 |
| **方案版本** | v2.0 |
| **创建日期** | 2024-12-19 |
| **更新日期** | 2024-12-XX |
| **适用范围** | Red Jasmine Framework - Product Domain |
| **文档状态** | 📝 设计阶段 |

---

## 一、方案概述

### 1.1 设计目标

为商品领域实现完整的多语言翻译体系，支持：
- ✅ 商品信息多语言翻译
- ✅ 属性、属性值、属性组多语言翻译
- ✅ 类目、品牌、标签多语言翻译
- ✅ 翻译回退机制（找不到翻译时使用默认语言）
- ✅ 翻译状态管理（待翻译、已翻译、已审核）
- ✅ 批量翻译支持（AI/API）

### 1.2 设计原则

```
核心原则：

✅ 主表保留：主表保留默认语言内容（zh-CN）
✅ 专用翻译表：每个实体有对应的 _translations 表
✅ 关联简单：1:N 关系，一次 JOIN 即可获取翻译
✅ 翻译回退：找不到翻译时使用默认语言
✅ 状态管理：支持翻译状态（待翻译、已翻译、已审核）
✅ 批量翻译：支持 AI/API 批量翻译工具
✅ 向后兼容：现有代码无需大幅修改，通过 Trait 扩展
```

### 1.3 技术选型

- **翻译包**：`astrotomic/laravel-translatable`（已在 support 包中引入）
- **实现方式**：基于 Laravel Translatable 包，封装为 `HasTranslations` Trait
- **数据库设计**：主表 + 翻译表（1:N 关系）

### 1.4 字段命名规范

#### **富文本详情字段命名**

根据主流开源电商平台的实践，统一使用 `description` 作为富文本详情字段名：

| 平台 | 字段名 | 说明 |
|------|--------|------|
| **Magento 2** | `description` | 富文本详情（HTML格式） |
| **WooCommerce** | `description` | 商品详细描述（富文本） |
| **PrestaShop** | `description` | 商品详细描述（HTML格式） |
| **Bagisto** | `description` | 富文本详情 |
| **行业标准** | `description` | ✅ 统一使用此字段名 |

**字段说明**：
- `description`：富文本详情（HTML格式，用于详情页），当前存储在 `products_extension.description` 字段

**注意**：当前代码中 `products_extension.description` 字段将在数据迁移时映射到 `product_translations.description` 字段。`products_extension.meta_title`、`meta_keywords`、`meta_description` 字段也将迁移到翻译表。

### 1.5 行业标准参考

主流开源电商平台的多语言设计实践：

#### **Magento 2**
- **Store View 机制**：每个 Store View 对应一种语言/地区
- **完整翻译支持**：商品的所有文本字段（标题、短描述、富文本详情）都支持多语言
- **字段命名**：使用 `description` 作为富文本详情字段（`short_description` 为简短描述）
- **实现方式**：使用 `catalog_product_entity_text` 等 EAV 表存储不同 Store View 的内容
- **特点**：富文本详情（`description`）完全支持多语言，是标准功能

#### **WooCommerce + WPML**
- **插件机制**：通过 WPML（WordPress Multilingual）插件实现多语言
- **翻译支持**：商品标题、描述、短描述、富文本详情都支持翻译
- **实现方式**：使用 `wp_postmeta` 表存储不同语言的翻译内容
- **特点**：富文本详情是必须翻译的字段之一

#### **PrestaShop**
- **多语言系统**：内置完整的多语言支持系统
- **翻译管理**：商品的所有文本字段都可以为每种语言设置不同内容
- **字段命名**：使用 `description`（富文本详情）和 `description_short`（简短描述）
- **实现方式**：使用 `ps_product_lang` 表存储多语言内容
- **特点**：`description`（富文本详情）和 `description_short` 字段都支持多语言

#### **Bagisto（Laravel 电商框架）**
- **Laravel Translatable**：基于 `astrotomic/laravel-translatable` 包
- **完整翻译**：商品描述、富文本详情等所有文本字段支持多语言
- **实现方式**：使用翻译表（`product_translations`）存储多语言内容
- **特点**：与我们的方案类似，富文本详情是标准翻译字段

#### **行业共识**

✅ **富文本详情必须支持多语言**，原因：
1. **用户体验**：不同语言用户需要看到本地化的商品详情
2. **SEO 优化**：多语言详情有助于不同地区的 SEO
3. **合规要求**：某些地区要求商品信息必须使用当地语言
4. **营销需求**：不同市场可能需要不同的营销文案和展示方式

✅ **行业标准做法**：
- 商品标题、广告语、富文本详情 → **必须翻译**
- SEO 相关字段（meta_title, meta_keywords, meta_description）→ **必须翻译**
- 商品图片、视频、结构化数据 → **通常不翻译**（但可能需要本地化）

---

## 二、需要翻译的实体清单

| 实体 | 主表 | 翻译表 | 可翻译字段数 | 优先级 |
|------|------|--------|--------------|--------|
| 商品 | products + products_extension | product_translations | 6个 | ⭐⭐⭐⭐⭐ |
| 属性 | product_attributes | product_attribute_translations | 4个 | ⭐⭐⭐⭐ |
| 属性值 | product_attribute_values | product_attribute_value_translations | 2个 | ⭐⭐⭐⭐ |
| 属性组 | product_attribute_groups | product_attribute_group_translations | 2个 | ⭐⭐⭐ |
| 类目 | product_categories | product_category_translations | 2个 | ⭐⭐⭐⭐⭐ |
| 分组 | product_groups | product_group_translations | 2个 | ⭐⭐⭐⭐ |
| 品牌 | product_brands | product_brand_translations | 3个 | ⭐⭐⭐⭐ |
| 标签 | product_tags | product_tag_translations | 2个 | ⭐⭐⭐ |

---

## 三、数据库表结构设计

### 3.1 商品翻译表（product_translations）

```sql
CREATE TABLE product_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码：zh-CN, en-US, de-DE, ja-JP',
    
    -- ========== 基础内容（来自 products 表）==========
    title VARCHAR(255) NOT NULL COMMENT '商品标题',
    slogan VARCHAR(255) NULL COMMENT '广告语/副标题',
    
    -- ========== 详情内容（来自 products_extension 表）==========
    description LONGTEXT NULL COMMENT '富文本详情（HTML格式，详细描述）',
    
    -- ========== SEO 相关（来自 products_extension 表）==========
    meta_title VARCHAR(255) NULL COMMENT 'SEO标题',
    meta_keywords VARCHAR(255) NULL COMMENT 'SEO关键词',
    meta_description TEXT NULL COMMENT 'SEO描述',
    
    -- ========== 翻译状态 ==========
    translation_status VARCHAR(32) DEFAULT 'pending' COMMENT '翻译状态：pending-待翻译, translated-已翻译, reviewed-已审核',
    translated_at TIMESTAMP NULL COMMENT '翻译完成时间',
    reviewed_at TIMESTAMP NULL COMMENT '审核完成时间',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_product_locale (product_id, locale),
    INDEX idx_locale (locale),
    INDEX idx_translation_status (translation_status),
    FULLTEXT INDEX idx_search (title, description),
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    
    COMMENT='商品-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2 属性翻译表（product_attribute_translations）

```sql
CREATE TABLE product_attribute_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_attribute_id BIGINT UNSIGNED NOT NULL COMMENT '属性ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    name VARCHAR(255) NOT NULL COMMENT '属性名称',
    description TEXT NULL COMMENT '属性描述',
    unit VARCHAR(50) NULL COMMENT '单位',
    alias VARCHAR(255) NULL COMMENT '别名',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_attribute_locale (product_attribute_id, locale),
    INDEX idx_locale (locale),
    
    FOREIGN KEY (product_attribute_id) REFERENCES product_attributes(id) ON DELETE CASCADE,
    
    COMMENT='商品属性-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.3 属性值翻译表（product_attribute_value_translations）

```sql
CREATE TABLE product_attribute_value_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_attribute_value_id BIGINT UNSIGNED NOT NULL COMMENT '属性值ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    value VARCHAR(255) NOT NULL COMMENT '属性值',
    alias VARCHAR(255) NULL COMMENT '别名',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_attribute_value_locale (product_attribute_value_id, locale),
    INDEX idx_locale (locale),
    
    FOREIGN KEY (product_attribute_value_id) REFERENCES product_attribute_values(id) ON DELETE CASCADE,
    
    COMMENT='商品属性值-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.4 属性组翻译表（product_attribute_group_translations）

```sql
CREATE TABLE product_attribute_group_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_attribute_group_id BIGINT UNSIGNED NOT NULL COMMENT '属性组ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    name VARCHAR(255) NOT NULL COMMENT '属性组名称',
    description TEXT NULL COMMENT '属性组描述',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_attribute_group_locale (product_attribute_group_id, locale),
    INDEX idx_locale (locale),
    
    FOREIGN KEY (product_attribute_group_id) REFERENCES product_attribute_groups(id) ON DELETE CASCADE,
    
    COMMENT='商品属性组-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.5 类目翻译表（product_category_translations）

**注意**：类目属于系统总类目，不需要SEO信息，仅翻译基础信息。

```sql
CREATE TABLE product_category_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_category_id BIGINT UNSIGNED NOT NULL COMMENT '类目ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    name VARCHAR(255) NOT NULL COMMENT '类目名称',
    description TEXT NULL COMMENT '类目描述',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_category_locale (product_category_id, locale),
    INDEX idx_locale (locale),
    FULLTEXT INDEX idx_search (name, description),
    
    FOREIGN KEY (product_category_id) REFERENCES product_categories(id) ON DELETE CASCADE,
    
    COMMENT='商品类目-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.6 品牌翻译表（product_brand_translations）

```sql
CREATE TABLE product_brand_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_brand_id BIGINT UNSIGNED NOT NULL COMMENT '品牌ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    name VARCHAR(255) NOT NULL COMMENT '品牌名称',
    description TEXT NULL COMMENT '品牌描述',
    slogan VARCHAR(255) NULL COMMENT '品牌口号',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_brand_locale (product_brand_id, locale),
    INDEX idx_locale (locale),
    
    FOREIGN KEY (product_brand_id) REFERENCES product_brands(id) ON DELETE CASCADE,
    
    COMMENT='商品品牌-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.7 分组翻译表（product_group_translations）

**注意**：商品分组是商户的前台分组表，需要翻译基础信息。

```sql
CREATE TABLE product_group_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_group_id BIGINT UNSIGNED NOT NULL COMMENT '分组ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    name VARCHAR(255) NOT NULL COMMENT '分组名称',
    description TEXT NULL COMMENT '分组描述',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_group_locale (product_group_id, locale),
    INDEX idx_locale (locale),
    FULLTEXT INDEX idx_search (name, description),
    
    FOREIGN KEY (product_group_id) REFERENCES product_groups(id) ON DELETE CASCADE,
    
    COMMENT='商品分组-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.8 标签翻译表（product_tag_translations）

```sql
CREATE TABLE product_tag_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_tag_id BIGINT UNSIGNED NOT NULL COMMENT '标签ID',
    locale VARCHAR(10) NOT NULL COMMENT '语言代码',
    
    -- ========== 可翻译字段 ==========
    name VARCHAR(255) NOT NULL COMMENT '标签名称',
    description TEXT NULL COMMENT '标签描述',
    
    translation_status VARCHAR(32) DEFAULT 'pending',
    translated_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_tag_locale (product_tag_id, locale),
    INDEX idx_locale (locale),
    
    FOREIGN KEY (product_tag_id) REFERENCES product_tags(id) ON DELETE CASCADE,
    
    COMMENT='商品标签-翻译表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.9 现有表字段调整

#### **products 表**
- **保留字段**：`title`, `slogan` 作为默认语言（zh-CN）内容保留在主表
- **无需翻译字段**：`slug`, `image`, `status`, `product_type` 等业务字段保留在主表
- **数据迁移**：`title`, `slogan` 字段数据将迁移到 `product_translations` 表（zh-CN）

#### **products_extension 表**
- **迁移字段**：以下字段需要多语言支持，将迁移到 `product_translations` 表：
  - `description` → `product_translations.description`（富文本详情）
  - `meta_title` → `product_translations.meta_title`（SEO标题）
  - `meta_keywords` → `product_translations.meta_keywords`（SEO关键词）
  - `meta_description` → `product_translations.meta_description`（SEO描述）
- **保留字段**：以下字段为结构化数据，保留在扩展表中，通常不需要翻译：
  - `images`（图片集）
  - `videos`（视频集）
  - `freight_templates`（运费模板）
  - `after_sales_services`（售后服务）
  - `basic_attrs`、`sale_attrs`、`customize_attrs`（属性数据）
  - `form`、`tools`、`extra`（扩展数据）
- **注意**：扩展表中的 `description`、`meta_title`、`meta_keywords`、`meta_description` 字段在迁移后仍保留，作为默认语言（zh-CN）的备份，但后续更新将优先写入翻译表

#### **product_attributes 表**
- **保留字段**：`name`, `description`, `unit` 作为默认语言内容（不删除，向后兼容）
- **翻译管理**：新增翻译通过 `product_attribute_translations` 表管理

#### **product_categories 表**
- **保留字段**：`name` 字段作为默认语言内容
- **翻译管理**：新增翻译通过 `product_category_translations` 表管理
- **注意**：类目表使用 `category()` 宏创建，包含 `name` 字段

#### **product_groups 表**
- **保留字段**：`name`, `description` 字段作为默认语言内容
- **翻译管理**：新增翻译通过 `product_group_translations` 表管理
- **注意**：商品分组是商户的前台分组表，使用 `category()` 宏创建，包含 `name` 和 `description` 字段

---

## 四、核心代码组件设计

### 4.1 HasTranslations Trait（Support 包）

**文件位置：** `packages/support/src/Domain/Models/Traits/HasTranslations.php`

**功能：**
- 封装 `astrotomic/laravel-translatable` 包的使用
- 提供统一的翻译接口
- 支持翻译回退机制
- 支持翻译状态管理

```php
<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;

/**
 * 多语言翻译 Trait
 * 
 * 基于 astrotomic/laravel-translatable 封装
 * 提供统一的翻译接口和回退机制
 */
trait HasTranslations
{
    use Translatable;

    /**
     * 获取翻译字段列表
     * 子类需要覆盖此方法
     */
    public function getTranslatableAttributes(): array
    {
        return $this->translatable ?? [];
    }

    /**
     * 获取默认语言
     */
    public function getDefaultLocale(): ?string
    {
        return $this->getLocale() ?: config('app.locale', 'zh-CN');
    }

    /**
     * 获取指定语言的翻译（带回退）
     * 
     * @param string|null $locale 语言代码，null 使用当前语言
     * @param bool $withFallback 是否回退到默认语言
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function translate(?string $locale = null, bool $withFallback = true)
    {
        $locale = $locale ?: $this->getLocale();
        
        if (!$withFallback) {
            return parent::translate($locale, false);
        }

        // 先尝试获取指定语言的翻译
        $translation = parent::translate($locale, false);
        
        // 如果没有找到，回退到默认语言
        if (!$translation && $locale !== $this->getDefaultLocale()) {
            $translation = parent::translate($this->getDefaultLocale(), false);
        }

        return $translation;
    }

    /**
     * 获取翻译字段值（带回退）
     * 
     * @param string $key 字段名
     * @param string|null $locale 语言代码
     * @return mixed
     */
    public function getTranslatedAttribute(string $key, ?string $locale = null)
    {
        $translation = $this->translate($locale);
        
        if ($translation && $translation->hasAttribute($key)) {
            return $translation->getAttribute($key);
        }

        // 回退到主表字段
        return $this->getAttribute($key);
    }

    /**
     * 设置翻译
     * 
     * @param string $locale 语言代码
     * @param array $attributes 翻译字段
     * @return $this
     */
    public function setTranslation(string $locale, array $attributes)
    {
        $this->translateOrNew($locale)->fill($attributes);
        return $this;
    }

    /**
     * 查询作用域：预加载翻译
     * 
     * @param Builder $query
     * @param string|null $locale 语言代码
     * @return Builder
     */
    public function scopeWithTranslation(Builder $query, ?string $locale = null): Builder
    {
        return $query->with(['translations' => function ($query) use ($locale) {
            if ($locale) {
                $query->where('locale', $locale);
            }
        }]);
    }

    /**
     * 查询作用域：按语言过滤
     * 
     * @param Builder $query
     * @param string $locale 语言代码
     * @return Builder
     */
    public function scopeHasTranslation(Builder $query, string $locale): Builder
    {
        return $query->whereHas('translations', function ($q) use ($locale) {
            $q->where('locale', $locale);
        });
    }
}
```

### 4.2 翻译状态枚举（Support 包）

**文件位置：** `packages/support/src/Domain/Models/Enums/TranslationStatusEnum.php`

```php
<?php

namespace RedJasmine\Support\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TranslationStatusEnum: string
{
    use EnumsHelper;

    case PENDING = 'pending';      // 待翻译
    case TRANSLATED = 'translated'; // 已翻译
    case REVIEWED = 'reviewed';     // 已审核

    public static function labels(): array
    {
        return [
            self::PENDING->value => '待翻译',
            self::TRANSLATED->value => '已翻译',
            self::REVIEWED->value => '已审核',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING->value => 'gray',
            self::TRANSLATED->value => 'blue',
            self::REVIEWED->value => 'green',
        ];
    }
}
```

### 4.3 模型修改示例

#### **Product 模型**

```php
<?php

namespace RedJasmine\Product\Domain\Product\Models;

use RedJasmine\Support\Domain\Models\Traits\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    /**
     * 可翻译字段
     * 
     * 字段来源：
     * - title, slogan: 来自 products 表
     * - description, meta_title, meta_keywords, meta_description: 来自 products_extension 表
     */
    public array $translatable = [
        'title',              // 商品标题（来自 products 表）
        'slogan',             // 广告语（来自 products 表）
        'description',        // 富文本详情（来自 products_extension 表）
        'meta_title',         // SEO标题（来自 products_extension 表）
        'meta_keywords',      // SEO关键词（来自 products_extension 表）
        'meta_description',   // SEO描述（来自 products_extension 表）
    ];

    /**
     * 翻译关联
     */
    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    /**
     * 获取翻译后的标题
     * 
     * @param string|null $locale
     * @return string
     */
    public function getTranslatedTitle(?string $locale = null): string
    {
        return $this->getTranslatedAttribute('title', $locale) ?: $this->title;
    }

    /**
     * 获取翻译后的富文本详情
     * 
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslatedDescription(?string $locale = null): ?string
    {
        // 优先从翻译表获取
        $description = $this->getTranslatedAttribute('description', $locale);
        
        // 如果翻译表中没有，从扩展表获取（向后兼容）
        if (!$description && $this->relationLoaded('extension') && $this->extension) {
            $description = $this->extension->description;
        }
        
        return $description;
    }
    
    /**
     * 获取翻译后的SEO标题
     * 
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslatedMetaTitle(?string $locale = null): ?string
    {
        $metaTitle = $this->getTranslatedAttribute('meta_title', $locale);
        
        // 向后兼容：从扩展表获取
        if (!$metaTitle && $this->relationLoaded('extension') && $this->extension) {
            $metaTitle = $this->extension->meta_title;
        }
        
        return $metaTitle;
    }
}
```

#### **ProductCategory 模型**

**注意**：类目属于系统总类目，仅翻译基础信息，不包含SEO字段。

```php
<?php

namespace RedJasmine\Product\Domain\Category\Models;

use RedJasmine\Support\Domain\Models\Traits\HasTranslations;

class ProductCategory extends Model
{
    use HasTranslations;

    /**
     * 可翻译字段
     * 
     * 类目仅翻译基础信息，不包含SEO字段
     */
    public array $translatable = [
        'name',        // 类目名称
        'description',  // 类目描述
    ];

    /**
     * 翻译关联
     */
    public function translations()
    {
        return $this->hasMany(ProductCategoryTranslation::class);
    }
}
```

#### **ProductGroup 模型**

**注意**：商品分组是商户的前台分组表，需要翻译基础信息。

```php
<?php

namespace RedJasmine\Product\Domain\Group\Models;

use RedJasmine\Support\Domain\Models\Traits\HasTranslations;

class ProductGroup extends Model
{
    use HasTranslations;

    /**
     * 可翻译字段
     * 
     * 商品分组是商户的前台分组表，需要翻译基础信息
     */
    public array $translatable = [
        'name',        // 分组名称
        'description', // 分组描述
    ];

    /**
     * 翻译关联
     */
    public function translations()
    {
        return $this->hasMany(ProductGroupTranslation::class);
    }
}
```

### 4.4 翻译模型示例

#### **ProductTranslation 模型**

```php
<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

class ProductTranslation extends Model
{
    protected $table = 'product_translations';

    protected $fillable = [
        'product_id',
        'locale',
        'title',              // 来自 products 表
        'slogan',             // 来自 products 表
        'description',        // 来自 products_extension 表（富文本详情）
        'meta_title',         // 来自 products_extension 表
        'meta_keywords',      // 来自 products_extension 表
        'meta_description',   // 来自 products_extension 表
        'translation_status',
        'translated_at',
        'reviewed_at',
    ];

    protected $casts = [
        'translation_status' => TranslationStatusEnum::class,
        'translated_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

---

## 五、API 接口调整方案

### 5.1 查询接口调整

#### **支持语言参数**

```php
// GET /api/products/{id}?locale=en-US
// GET /api/products?locale=en-US

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        // 预加载翻译
        $product->load(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }]);

        return new ProductResource($product);
    }
}
```

#### **ProductResource 调整**

```php
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = $request->get('locale', app()->getLocale());
        
        return [
            'id' => $this->id,
            'title' => $this->getTranslatedTitle($locale),
            'slogan' => $this->getTranslatedAttribute('slogan', $locale),
            'description' => $this->getTranslatedDescription($locale),  // 富文本详情
            'meta_title' => $this->getTranslatedMetaTitle($locale),
            'meta_keywords' => $this->getTranslatedAttribute('meta_keywords', $locale),
            'meta_description' => $this->getTranslatedAttribute('meta_description', $locale),
            // ... 其他字段
        ];
    }
}
```

### 5.2 创建/更新接口调整

#### **支持多语言数据**

```php
class ProductCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],  // 富文本详情（HTML格式）
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            
            // 翻译数据
            'translations' => ['nullable', 'array'],
            'translations.*.locale' => ['required', 'string'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slogan' => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],  // 富文本详情
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_keywords' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
        ];
    }
}

class ProductCreateCommandHandler extends CommandHandler
{
    public function handle(ProductCreateCommand $command): Product
    {
        $this->beginDatabaseTransaction();
        
        try {
            $product = $this->service->newModel();
            $product = $this->service->transformer->transform($command, $product);
            $this->service->repository->store($product);
            
            // 保存翻译
            if ($command->translations) {
                foreach ($command->translations as $translation) {
                    $product->setTranslation(
                        $translation['locale'],
                        $translation
                    );
                }
                $product->save();
            }
            
            $this->commitDatabaseTransaction();
            return $product;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
```

---

## 六、数据迁移方案

### 6.1 迁移步骤

#### **Phase 1: 创建翻译表（Week 1）**

1. 创建所有翻译表的迁移文件
2. 创建翻译状态枚举类
3. 数据库迁移测试

#### **Phase 2: 实现 Trait 和模型（Week 2）**

1. 创建 `HasTranslations` Trait
2. 创建所有翻译模型类
3. 修改现有模型，添加 `HasTranslations` Trait
4. 配置可翻译字段

#### **Phase 3: 数据迁移（Week 3）**

1. 将现有主表数据迁移到翻译表（zh-CN）
2. 数据验证和修复

#### **Phase 4: API 调整（Week 4）**

1. 调整查询接口，支持语言参数
2. 调整创建/更新接口，支持翻译数据
3. 调整 Resource 类，返回翻译内容

#### **Phase 5: 测试和优化（Week 5）**

1. 单元测试
2. 集成测试
3. 性能优化
4. 文档编写

### 6.2 数据迁移脚本示例

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateProductTranslations extends Migration
{
    public function up()
    {
        // 步骤1：将现有 products 表的基础字段迁移到 product_translations 表（zh-CN）
        DB::statement("
            INSERT INTO product_translations (
                product_id, locale, title, slogan, translation_status, created_at, updated_at
            )
            SELECT 
                id, 'zh-CN', title, slogan, 'reviewed', created_at, updated_at
            FROM products
            WHERE title IS NOT NULL
        ");
        
        // 步骤2：将扩展表的详情和SEO字段更新到翻译表
        DB::statement("
            UPDATE product_translations pt
            INNER JOIN products_extension pe ON pt.product_id = pe.id
            SET 
                pt.description = pe.description,
                pt.meta_title = pe.meta_title,
                pt.meta_keywords = pe.meta_keywords,
                pt.meta_description = pe.meta_description,
                pt.updated_at = GREATEST(pt.updated_at, pe.updated_at)
            WHERE pt.locale = 'zh-CN'
        ");
    }

    public function down()
    {
        DB::table('product_translations')->where('locale', 'zh-CN')->delete();
    }
}
```

---

## 七、翻译服务设计

### 7.1 TranslationService（可选）

**文件位置：** `packages/product/src/Domain/Translation/Services/TranslationService.php`

**功能：**
- 批量翻译
- AI 翻译集成
- 翻译状态管理

```php
<?php

namespace RedJasmine\Product\Domain\Translation\Services;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;

class TranslationService
{
    /**
     * 批量翻译商品
     * 
     * @param Product $product
     * @param array $targetLocales 目标语言列表
     * @return void
     */
    public function translateProduct(Product $product, array $targetLocales): void
    {
        foreach ($targetLocales as $locale) {
            if ($product->hasTranslation($locale)) {
                continue; // 已存在翻译，跳过
            }

            $translation = $this->translateText(
                $product->title,
                $product->getDefaultLocale(),
                $locale
            );

            $product->setTranslation($locale, [
                'title' => $translation['title'],
                'slogan' => $translation['slogan'] ?? null,
                'description' => $translation['description'] ?? null,  // 富文本详情
                'meta_title' => $translation['meta_title'] ?? null,
                'meta_keywords' => $translation['meta_keywords'] ?? null,
                'meta_description' => $translation['meta_description'] ?? null,
                'translation_status' => TranslationStatusEnum::TRANSLATED,
                'translated_at' => now(),
            ]);
        }

        $product->save();
    }

    /**
     * 翻译文本（调用 AI 翻译 API）
     * 
     * @param string $text
     * @param string $sourceLocale
     * @param string $targetLocale
     * @return array
     */
    protected function translateText(string $text, string $sourceLocale, string $targetLocale): array
    {
        // TODO: 集成 AI 翻译 API（OpenAI、DeepL 等）
        // 这里只是示例
        return [
            'title' => $text, // 实际应该调用翻译 API
            'description' => null,
        ];
    }
}
```

---

## 八、注意事项

### 8.1 向后兼容

1. **主表字段保留**：主表字段作为默认语言内容保留，不删除
2. **查询回退**：查询时优先使用翻译表，如果没有则回退到主表
3. **API 兼容**：现有 API 接口保持兼容，未指定语言时使用默认语言

### 8.2 性能优化

1. **索引优化**：
   - 翻译表建立 `(entity_id, locale)` 唯一索引
   - 建立 `locale` 索引
   - 全文索引用于搜索

2. **查询优化**：
   - 使用 `withTranslation()` 预加载翻译
   - 避免 N+1 查询问题

3. **缓存策略**：
   - 翻译内容可以缓存（Redis）
   - 缓存 key：`translation:{entity}:{id}:{locale}`

### 8.3 数据一致性

1. **事务保证**：翻译操作在事务中执行
2. **级联删除**：翻译表设置外键级联删除
3. **数据验证**：翻译数据需要验证完整性

---

## 九、实施路线图

### Phase 1: 基础架构（Week 1-2）⭐⭐⭐⭐⭐

**任务：**
1. 创建 `HasTranslations` Trait
2. 创建翻译状态枚举
3. 创建所有翻译表迁移文件
4. 数据库迁移测试

**验收标准：**
- ✅ Trait 功能正常
- ✅ 翻译表创建成功
- ✅ 数据库迁移无错误

### Phase 2: 模型实现（Week 3-4）⭐⭐⭐⭐⭐

**任务：**
1. 创建所有翻译模型类
2. 修改现有模型，添加 `HasTranslations` Trait
3. 配置可翻译字段
4. 单元测试

**验收标准：**
- ✅ 所有模型支持翻译
- ✅ 翻译回退正常工作
- ✅ 单元测试覆盖率 >80%

### Phase 3: 数据迁移（Week 5）⭐⭐⭐⭐

**任务：**
1. 编写数据迁移脚本
2. 执行数据迁移
3. 数据验证和修复

**验收标准：**
- ✅ 现有数据成功迁移到翻译表
- ✅ 数据完整性验证通过

### Phase 4: API 调整（Week 6-7）⭐⭐⭐⭐

**任务：**
1. 调整查询接口，支持语言参数
2. 调整创建/更新接口，支持翻译数据
3. 调整 Resource 类
4. 集成测试

**验收标准：**
- ✅ API 接口支持多语言
- ✅ 翻译数据正确返回
- ✅ 集成测试通过

### Phase 5: 翻译工具（Week 8）⭐⭐⭐

**任务：**
1. 实现 TranslationService
2. 集成 AI 翻译（可选）
3. Filament 管理后台（可选）
4. 文档编写

**验收标准：**
- ✅ 翻译服务功能正常
- ✅ 完整的使用文档

---

## 十、总结

本方案基于 `astrotomic/laravel-translatable` 包，通过封装 `HasTranslations` Trait 实现统一的多语言翻译体系。方案特点：

- ✅ **符合行业标准**：参考 Magento、WooCommerce、PrestaShop 等主流平台的设计，富文本详情支持多语言是标准做法
- ✅ **向后兼容**：主表字段保留，现有代码无需大幅修改
- ✅ **易于使用**：通过 Trait 简单扩展，配置可翻译字段即可
- ✅ **性能优化**：合理的索引和缓存策略
- ✅ **翻译回退**：找不到翻译时自动回退到默认语言
- ✅ **状态管理**：支持翻译状态跟踪
- ✅ **易于扩展**：支持批量翻译和 AI 翻译集成
- ✅ **完整翻译**：商品标题、广告语、**富文本详情**、SEO 字段等核心内容都支持多语言

---

---

## 十一、当前实现状态

### 11.1 已实现功能
- ✅ `astrotomic/laravel-translatable` 包已在 support 包中引入（版本 ^11.16）
- ✅ 数据库表结构已明确（products 表、products_extension 表）

### 11.2 待实现功能
- ⏳ `HasTranslations` Trait（Support 包）
- ⏳ `TranslationStatusEnum` 枚举（Support 包）
- ⏳ 所有翻译表的迁移文件
- ⏳ 所有翻译模型类
- ⏳ 现有模型的 Trait 集成
- ⏳ 数据迁移脚本
- ⏳ API 接口调整

### 11.3 字段映射关系

| 翻译表字段 | 来源表 | 来源字段 | 说明 |
|-----------|--------|---------|------|
| `title` | `products` | `title` | 商品标题 |
| `slogan` | `products` | `slogan` | 广告语 |
| `description` | `products_extension` | `description` | 富文本详情 |
| `meta_title` | `products_extension` | `meta_title` | SEO标题 |
| `meta_keywords` | `products_extension` | `meta_keywords` | SEO关键词 |
| `meta_description` | `products_extension` | `meta_description` | SEO描述 |

---

**文档状态：** 📝 设计完成，待评审

**© 2024 Red Jasmine Framework. All Rights Reserved.**

