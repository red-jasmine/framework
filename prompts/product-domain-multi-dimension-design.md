# Red Jasmine 商品领域多维度设计方案

## 文档信息

| 项目 | 内容 |
|------|------|
| **方案名称** | 商品领域多维度设计方案（三维度 + 多价格 + 多语言） |
| **方案版本** | v1.0 |
| **创建日期** | 2024-11-13 |
| **文档作者** | Red Jasmine Team |
| **适用范围** | Red Jasmine Framework - Product Domain |
| **文档状态** | 📝 Draft（草稿） |

---

## 目录

- [一、方案概述](#一方案概述)
- [二、三维度商品模型设计](#二三维度商品模型设计)
- [三、多价格体系设计](#三多价格体系设计)
- [四、多市场库存体系设计](#四多市场库存体系设计)
- [五、多语言体系设计](#五多语言体系设计)
- [六、数据库表设计清单](#六数据库表设计清单)
- [七、详细表结构设计](#七详细表结构设计)
- [八、核心代码组件](#八核心代码组件)
- [九、实施路线图](#九实施路线图)
- [十、技术栈说明](#十技术栈说明)
- [十一、预期收益](#十一预期收益)
- [十二、风险评估](#十二风险评估)
- [十三、方案总结](#十三方案总结)
- [附录](#附录)

---

## 一、方案概述

### 1.1 设计目标

构建一个支持**多业务平台、多市场、多业务线、多价格、多语言**的企业级商品中心，满足以下场景：

| 能力 | 说明 |
|------|------|
| ✅ **多业务平台支持** | 商城、外卖、点餐、旅游、企业采购等不同业务场景 |
| ✅ **跨境电商** | 支持多国家/地区站点，多货币定价 |
| ✅ **多经营模式** | 平台自营、POP商家、加盟商等不同经营主体 |
| ✅ **灵活定价** | 支持市场定价、会员定价、阶梯定价、促销定价 |
| ✅ **多语言体系** | 商品信息、属性、类目的完整多语言支持 |

---

### 1.2 核心设计理念

```
┌──────────────────────────────────────────────────────────┐
│                   三维度层级设计                          │
├──────────────────────────────────────────────────────────┤
│                                                           │
│  platform (业务平台)  ← 顶层，决定业务场景和市场范围    │
│      ↓                                                    │
│  market (市场)         ← 中层，受业务平台约束            │
│      ↓                                                    │
│  biz (业务线)          ← 底层，经营模式                  │
│                                                           │
│  层级关系：业务平台决定可用的 market 范围                 │
│  示例：mall 业务平台支持多国市场，takeaway 可能只有中国  │
└──────────────────────────────────────────────────────────┘
```

**设计原则：**

1. **层级性**：platform → market → biz，存在约束关系
2. **业务平台优先**：业务平台决定了支持的市场范围
3. **可扩展性**：新增维度值无需修改代码结构
4. **向后兼容**：保留默认值，兼容现有数据
5. **性能优先**：合理索引和缓存策略
6. **用户无感知**：后端技术概念，前端转换为用户友好展示

**层级约束示例：**

```
mall（商城业务平台）
  ├─ 支持市场：cn, us, uk, de, fr, jp... （全球化）
  └─ 业务线：self, pop, franchise

takeaway（外卖业务平台）
  ├─ 支持市场：cn （仅中国）
  └─ 业务线：self, pop

travel（旅游业务平台）
  ├─ 支持市场：cn, us, jp, th （旅游目的地）
  └─ 业务线：self, pop, agent
```

---

### 1.3 参考平台

本方案参考了以下开源电商平台的设计：

| 平台 | 参考内容 | 采用程度 |
|------|----------|----------|
| **Magento 2** | EAV模型、多语言设计 | ⭐⭐⭐ 部分参考 |
| **PrestaShop** | 主表+语言表设计 | ⭐⭐⭐⭐⭐ 核心设计 |
| **Bagisto** | Laravel生态实现 | ⭐⭐⭐⭐ 技术实现 |
| **京东/美团** | 业务场景划分 | ⭐⭐⭐⭐ 业务模型 |

---

## 二、三维度商品模型设计

### 2.1 维度定义

#### **维度1：platform（业务平台）**

| 属性 | 值 |
|------|-----|
| **英文名** | platform |
| **中文名** | 业务平台 |
| **数据类型** | VARCHAR(64) |
| **默认值** | 'default' |
| **是否必填** | 是 |

**定义：** 商品所属的业务平台类型，决定商品的功能特性、处理流程和支持的市场范围。业务平台是公司内部不同的业务线或业务场景，如低价商城、高端商城、二手平台等。

**典型取值：**

| 值 | 标签 | 说明 | 典型应用 |
|----|------|------|----------|
| `default` | 默认 | 通用平台 | 默认兼容 |
| `mall` | 商城平台 | 标准电商平台 | 京东、天猫 |
| `low_price` | 低价商城 | 低价商品商城平台 | 拼多多、特价商城 |
| `luxury` | 高端商城 | 高端首饰商城平台 | 奢侈品电商 |
| `secondhand` | 二手平台 | 区域性二手平台 | 闲鱼、转转 |
| `takeaway` | 外卖平台 | 餐饮外卖配送 | 美团外卖、饿了么 |
| `dine_in` | 堂食/点餐 | 门店点餐系统 | 扫码点餐小程序 |
| `travel` | 旅游平台 | 旅游产品 | 携程、去哪儿 |
| `hotel` | 酒店平台 | 酒店预订 | 酒店预订系统 |
| `ticket` | 门票平台 | 景区门票 | 景区门票系统 |
| `b2b` | 企业采购平台 | 批量采购 | 1688、阿里国际站 |
| `service` | 服务平台 | 服务类商品 | O2O服务 |
| `booking` | 预订平台 | 预约类商品 | 餐厅预订、车票 |

**影响范围：**

- **功能特性**：UI展示、必填字段、功能模块
- **处理流程**：订单状态流转、库存扣减方式、时效管理
- **业务规则**：退款规则、评价维度、支付方式

---

#### **维度2：market（市场站点）**

| 属性 | 值 |
|------|-----|
| **英文名** | market |
| **中文名** | 市场站点 |
| **数据类型** | VARCHAR(64) |
| **默认值** | 'default' |
| **是否必填** | 是 |

**定义：** 商品销售的地域市场或国家站点，决定货币、语言、税费、物流等地域属性。

**典型取值：**

| 值 | 标签 | 货币 | 语言 | 时区 |
|----|------|------|------|------|
| `default` | 默认 | CNY | zh-CN | Asia/Shanghai |
| `cn` | 中国站 | CNY | zh-CN | Asia/Shanghai |
| `us` | 美国站 | USD | en-US | America/New_York |
| `uk` | 英国站 | GBP | en-GB | Europe/London |
| `de` | 德国站 | EUR | de-DE | Europe/Berlin |
| `fr` | 法国站 | EUR | fr-FR | Europe/Paris |
| `jp` | 日本站 | JPY | ja-JP | Asia/Tokyo |
| `kr` | 韩国站 | KRW | ko-KR | Asia/Seoul |
| `au` | 澳大利亚站 | AUD | en-AU | Australia/Sydney |
| `ca` | 加拿大站 | CAD | en-CA | America/Toronto |
| `sg` | 新加坡站 | SGD | en-SG | Asia/Singapore |

**影响范围：**

- **货币相关**：默认货币、价格显示格式、汇率转换
- **语言相关**：默认语言、翻译内容选择、日期时间格式
- **物流相关**：物流公司、配送时效、运费计算规则
- **税费相关**：税率、税费计算方式、发票类型
- **合规相关**：海关编码、进出口限制、商品资质要求

---

#### **维度3：biz（业务线）**

| 属性 | 值 |
|------|-----|
| **英文名** | biz |
| **中文名** | 业务线/经营模式 |
| **数据类型** | VARCHAR(64) |
| **默认值** | 'default' |
| **是否必填** | 是 |

**定义：** 商品的经营主体和模式，决定结算方式、权限控制、运营策略。

**典型取值：**

| 值 | 标签 | 佣金率 | 说明 | 典型应用 |
|----|------|--------|------|----------|
| `default` | 默认 | 0% | 默认模式 | 默认兼容 |
| `self` | 平台自营 | 0% | 平台采购销售 | 京东自营 |
| `pop` | POP商家 | 3-10% | 第三方商家 | 天猫店铺 |
| `franchise` | 加盟商 | 2-5% | 加盟合作 | 便利店加盟 |
| `wholesale` | 批发商 | 1-3% | 大批量采购 | 1688批发 |
| `agent` | 代理商 | 3-7% | 品牌代理 | 区域代理 |
| `distributor` | 分销商 | 2-5% | 分销模式 | 社交电商 |

**影响范围：**

- **结算相关**：佣金比例、结算周期、结算主体
- **权限相关**：数据权限、功能权限、操作权限
- **运营相关**：商品审核、活动参与、展示权重
- **财务相关**：发票开具、退款流程、对账流程
- **用户展示**：店铺标识、物流信息、信任度

---

### 2.2 业务平台与市场的层级关系

#### **业务平台决定市场范围**

不同业务平台支持的市场范围不同，这是业务特性决定的：

| 业务平台 | 支持的市场 | 原因 |
|---------|-----------|------|
| `mall` (商城业务平台) | cn, us, uk, de, fr, jp, kr... | 全球化电商，支持跨境 |
| `low_price` (低价商城业务平台) | cn | 主要面向中国市场 |
| `luxury` (高端商城业务平台) | cn, us, uk, de, fr, jp... | 高端市场全球化 |
| `secondhand` (二手业务平台) | cn | 区域性业务，仅中国 |
| `takeaway` (外卖业务平台) | cn | 配送范围限制，仅中国 |
| `dine_in` (堂食业务平台) | cn | 门店业务，仅中国 |
| `travel` (旅游业务平台) | cn, us, jp, th, sg... | 旅游目的地国家 |
| `hotel` (酒店业务平台) | cn, us, jp, th, sg... | 酒店所在国家 |
| `b2b` (企业采购业务平台) | cn, us, uk, de... | 企业采购市场 |

#### **业务平台-市场配置**

```php
// config/platforms.php

return [
    'mall' => [
        'name' => '商城业务平台',
        'supported_markets' => ['cn', 'us', 'uk', 'de', 'fr', 'jp', 'kr', 'au', 'ca', 'sg'],
        'default_market' => 'cn',
        'features' => ['delivery', 'return', 'international_shipping'],
    ],
    
    'low_price' => [
        'name' => '低价商城业务平台',
        'supported_markets' => ['cn'],  // 主要面向中国市场
        'default_market' => 'cn',
        'features' => ['bulk_discount', 'group_buying'],
    ],
    
    'luxury' => [
        'name' => '高端商城业务平台',
        'supported_markets' => ['cn', 'us', 'uk', 'de', 'fr', 'jp'],
        'default_market' => 'cn',
        'features' => ['authenticity_guarantee', 'luxury_packaging'],
    ],
    
    'secondhand' => [
        'name' => '二手业务平台',
        'supported_markets' => ['cn'],  // 区域性业务
        'default_market' => 'cn',
        'features' => ['quality_inspection', 'transaction_guarantee'],
    ],
    
    'takeaway' => [
        'name' => '外卖业务平台',
        'supported_markets' => ['cn'],  // 仅支持中国
        'default_market' => 'cn',
        'features' => ['real_time_delivery', 'rider_tracking'],
    ],
    
    'dine_in' => [
        'name' => '堂食业务平台',
        'supported_markets' => ['cn'],  // 仅支持中国
        'default_market' => 'cn',
        'features' => ['table_management', 'kitchen_printing'],
    ],
    
    'travel' => [
        'name' => '旅游业务平台',
        'supported_markets' => ['cn', 'us', 'jp', 'th', 'sg', 'kr', 'fr', 'uk'],
        'default_market' => 'cn',
        'features' => ['calendar_booking', 'traveler_info', 'refund_rules'],
    ],
    
    'b2b' => [
        'name' => '企业采购业务平台',
        'supported_markets' => ['cn', 'us', 'uk', 'de', 'jp'],
        'default_market' => 'cn',
        'features' => ['bulk_order', 'invoice', 'credit_payment'],
    ],
];
```

---

### 2.3 三维度组合示例

| market | biz | platform | 业务场景描述 | 典型业务平台 |
|--------|-----|----------|--------------|------------|
| cn | self | mall | 中国站平台自营商城商品 | 京东自营 |
| us | self | mall | 美国站平台自营商城商品 | Amazon Self |
| cn | pop | mall | 中国站第三方商城店铺 | 天猫店铺 |
| cn | pop | low_price | 中国站低价商城商品 | 拼多多 |
| cn | pop | luxury | 中国站高端商城商品 | 奢侈品电商 |
| cn | pop | secondhand | 中国站二手业务平台商品 | 闲鱼 |
| cn | pop | takeaway | 中国站第三方外卖商家 | 美团外卖商家 |
| cn | self | takeaway | 中国站平台自营外卖 | 美团优选 |
| cn | pop | dine_in | 中国站商家堂食点餐 | 扫码点餐 |
| de | pop | mall | 德国站第三方商城 | 德国电商 |
| jp | self | b2b | 日本站企业采购自营 | 日本B2B |
| cn | pop | travel | 中国站第三方旅游产品 | 携程商家 |
| us | franchise | mall | 美国站加盟商商品 | 加盟店 |

---

## 三、多价格体系设计

### 3.1 设计原则

```
核心原则：

✅ 基准价格：products 表保留基准价格（主市场默认价格）
✅ 多维定价：product_prices 表管理多维度价格组合（market、store、user_level）
✅ 业务平台隔离：商品本身已有 platform 字段，价格表不包含 platform 维度
✅ 业务线无关：价格体系与业务线（biz）无关，所有业务线使用相同的价格体系
✅ 渠道无关：价格体系不包含 channel 维度，渠道差异化定价通过促销系统实现
✅ 门店差异化：通过 store 维度区分不同门店价格，支持默认门店（`default`）与具体门店编码
✅ 用户等级定价：通过 user_level 维度区分不同用户等级的价格（普通价、VIP价、黄金会员价等），不使用单独的 member_price 字段
✅ 灵活匹配：支持维度通配符和优先级匹配
✅ 职责分离：基础价格体系定义基准价格，促销系统处理渠道、时间、条件等营销策略
✅ 性能优化：合理索引、查询缓存
```

---

### 3.2 价格表结构设计

**设计说明：**
- **不包含业务平台维度**：商品本身已有 `platform` 字段，一个商品只能属于唯一业务平台，价格表通过 `product_id` 关联即可获取商品所属的业务平台
- **不包含业务线维度**：价格体系与业务线（biz）无关，所有业务线使用相同的价格体系
- **不包含渠道维度**：价格体系不包含 channel 维度，渠道差异化定价通过促销系统实现（如小程序专享价、APP首单优惠等）
- **价格维度**：market（市场）、store（门店）、user_level（用户等级）
- **门店定价**：store 维度支持 `default`（通用门店）、具体门店编码（如 `store_001`），以及 `*` 通配符，满足连锁门店差异化定价需求
- **用户等级定价**：通过 `user_level` 维度区分不同用户等级的价格，如 `default`（普通价）、`vip`（VIP价）、`gold`（黄金会员价）等，不再使用单独的 `member_price` 字段

```sql
CREATE TABLE product_prices (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID（通过 product_id 可获取商品的 platform）',
    variant_id BIGINT UNSIGNED NULL COMMENT 'SKU ID',
    
    -- ========== 价格维度（支持通配符 *） ==========
    market VARCHAR(64) DEFAULT '*' COMMENT '市场：cn, us, de, *',
    store VARCHAR(64) DEFAULT '*' COMMENT '门店：default-默认门店，store_xxx-具体门店，*-全部门店',
    user_level VARCHAR(32) DEFAULT '*' COMMENT '用户等级：default-普通, vip-VIP, gold-黄金会员, platinum-白金会员, *',
    
    -- ========== 价格信息 ==========
    currency CHAR(3) NOT NULL COMMENT '货币：CNY, USD, EUR',
    price DECIMAL(12, 2) NOT NULL COMMENT '销售价（根据 user_level 不同而不同，如普通价、VIP价、黄金会员价等）',
    market_price DECIMAL(12, 2) NULL COMMENT '市场价',
    cost_price DECIMAL(12, 2) NULL COMMENT '成本价',
    
    -- ========== 价格规则 ==========
    price_type VARCHAR(32) DEFAULT 'standard' COMMENT '价格类型',
    start_time TIMESTAMP NULL COMMENT '生效时间',
    end_time TIMESTAMP NULL COMMENT '失效时间',
    quantity_tiers JSON NULL COMMENT '阶梯价格',
    priority INT DEFAULT 0 COMMENT '优先级',
    
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_product_dimensions (product_id, market, store, user_level),
    INDEX idx_product_variant (product_id, variant_id),
    
    COMMENT='商品-多维度价格表'
);
```

#### **价格数据示例**

通过 `user_level` 维度区分不同用户等级的价格：

| product_id | market | store | user_level | price | 说明 |
|-----------|--------|-------|------------|-------|------|
| 1001 | cn | default | default | 100.00 | 中国市场通用门店普通用户价格 |
| 1001 | cn | default | vip | 90.00 | 中国市场通用门店VIP用户价格 |
| 1001 | cn | store_s001 | default | 95.00 | 中国市场上海徐汇门店普通价 |
| 1001 | cn | store_s001 | vip | 88.00 | 中国市场上海徐汇门店VIP价 |
| 1001 | cn | store_bj02 | gold | 83.00 | 中国市场北京国贸门店黄金会员价 |
| 1001 | cn | default | platinum | 80.00 | 中国市场白金会员价格 |
| 1001 | us | default | default | 15.00 | 美国市场普通用户价格（USD） |
| 1001 | us | store_la01 | vip | 13.50 | 美国市场洛杉矶门店VIP价（USD） |

**渠道差异化定价示例（通过促销系统实现）：**

| promotion_id | channel | discount_type | discount_value | 说明 |
|-------------|---------|--------------|----------------|------|
| P001 | mini | percentage | 10% | 小程序专享价：VIP用户 90元 × 0.9 = 81元 |
| P002 | app | fixed_amount | 5.00 | APP首单优惠：VIP用户 90元 - 5元 = 85元 |
| P003 | web | percentage | 8% | Web端会员日：VIP用户 90元 × 0.92 = 82.8元 |

**设计优势：**
- ✅ 支持多级会员体系（普通、VIP、黄金、白金等）
- ✅ 价格体系简化，只包含核心维度（市场、用户等级）
- ✅ 概念清晰，避免 `member_price` 字段的重复
- ✅ 查询简单，只需根据 `user_level` 匹配即可
- ✅ 职责分离：基础价格体系定义基准价格，渠道差异化通过促销系统实现
- ✅ 符合行业实践：与 Magento、Shopify 等主流系统一致

---

### 3.3 价格匹配优先级算法

```
价格查询优先级规则（从高到低）：

1. priority 字段值（数字越大越优先）
2. 精确匹配度（匹配的维度越多越优先）
3. 时间有效性检查
4. 状态检查
5. 回退机制（使用 products.price）

匹配度计算：
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
market='cn'      → 非通配符 +1000 分
user_level='vip' → 非通配符 +1    分

注意：
1. 业务平台（platform）不在价格维度中，因为商品本身已有 platform 字段，
   查询价格时通过 product_id 关联 products 表即可获取商品所属的业务平台。
2. 门店（store）作为新增维度，用于覆盖连锁门店差异化定价场景，支持 `default`、具体门店编码以及 `*` 通配符。
3. 业务线（biz）不在价格维度中，价格体系与业务线无关，所有业务线使用相同的价格体系。
4. 渠道（channel）不在价格维度中，渠道差异化定价通过促销系统实现。
   原因：渠道差异化定价具有时间限制、条件限制等特点，更适合通过促销系统处理。
   示例：小程序专享价通过促销规则实现，而不是在基础价格表中设置 channel='mini'。
5. 用户等级定价：通过 user_level 维度区分不同用户等级的价格，不再使用单独的 member_price 字段。
   示例：user_level='default' → price=100（普通价），user_level='vip' → price=90（VIP价），user_level='gold' → price=85（黄金会员价）
```

---

## 四、多市场库存体系设计

### 4.1 设计原则

```
核心原则：

✅ 基准库存：product_variants 表保留总库存（所有市场共享库存或默认市场库存）
✅ 多市场库存：product_stocks 表管理不同市场、门店的库存分配
✅ 业务平台隔离：商品本身已有 platform 字段，库存表通过 product_id 关联即可获取商品所属的业务平台
✅ 业务线无关：库存体系与业务线（biz）无关，所有业务线使用相同的库存体系
✅ 库存分配策略：支持共享库存、独立库存两种模式，并可叠加门店维度
✅ 库存扣减：支持按市场/门店精细扣减库存，支持库存锁定和释放
✅ 库存同步：支持库存自动同步和手动分配
✅ 性能优化：合理索引、查询缓存、库存预扣机制
```

---

### 4.2 库存分配策略

#### **策略1：共享库存模式（Shared Stock）**

**定义：** 所有市场共享同一份库存，任一市场售出后，所有市场的可用库存同步减少。

**适用场景：**
- 跨境电商，商品从同一仓库发货
- 多市场销售同一批商品
- 库存管理简单，无需区分市场

**特点：**
- ✅ 库存利用率高，避免库存分散
- ✅ 管理简单，无需分配库存
- ✅ 适合库存充足的商品

**示例：**
```
商品A总库存：1000件
中国市场售出：50件
美国市场可用库存：950件（自动同步）
```

---

#### **策略2：独立库存模式（Independent Stock）**

**定义：** 每个市场有独立的库存分配，市场间库存互不影响。

**适用场景：**
- 不同市场有独立的仓库或供应商
- 需要控制各市场的库存分配
- 某些市场有特殊库存限制

**特点：**
- ✅ 库存分配灵活，可精确控制
- ✅ 市场间库存隔离，互不影响
- ⚠️ 需要手动分配库存

**示例：**
```
商品A总库存：1000件
中国市场分配：600件
美国市场分配：300件
德国市场分配：100件

中国市场售出：50件 → 中国市场剩余：550件
美国市场可用库存：300件（不受影响）
```

---

#### **策略3：混合模式（Hybrid）**

**定义：** 部分市场共享库存，部分市场独立库存。

**适用场景：**
- 某些市场共享仓库，某些市场独立仓库
- 需要灵活配置不同市场的库存策略

**特点：**
- ✅ 灵活性最高
- ⚠️ 管理复杂度较高

---

### 4.3 库存表结构设计

**设计说明：**
- **不包含业务平台维度**：商品本身已有 `platform` 字段，库存表通过 `product_id` 关联即可获取商品所属的业务平台
- **不包含业务线维度**：库存体系与业务线（biz）无关，所有业务线使用相同的库存体系
- **库存维度**：market（市场）、store（门店）
- **门店库存**：store 维度支持默认门店或具体门店编码（如 `store_sz01`），便于对不同门店库存进行精细化分配，可与 market 配合形成组合
- **库存分配模式**：通过 `allocation_mode` 字段控制（shared-共享、independent-独立）

```sql
CREATE TABLE product_stocks (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL COMMENT '商品ID（通过 product_id 可获取商品的 platform）',
    variant_id BIGINT UNSIGNED NULL COMMENT 'SKU ID',
    
    -- ========== 库存维度 ==========
    market VARCHAR(64) NOT NULL COMMENT '市场：cn, us, de, *（*表示共享库存）',
    store VARCHAR(64) NOT NULL DEFAULT '*' COMMENT '门店：default-默认门店，store_xxx-具体门店，* 表示门店共享',
    
    -- ========== 库存分配模式 ==========
    allocation_mode VARCHAR(32) DEFAULT 'shared' COMMENT '分配模式：shared-共享库存, independent-独立库存',
    
    -- ========== 库存数量 ==========
    total_stock BIGINT DEFAULT 0 COMMENT '总库存（独立模式下为该市场分配的总库存）',
    available_stock BIGINT DEFAULT 0 COMMENT '可用库存',
    locked_stock BIGINT DEFAULT 0 COMMENT '锁定库存（已下单未支付）',
    reserved_stock BIGINT DEFAULT 0 COMMENT '预留库存（已支付待发货）',
    sold_stock BIGINT DEFAULT 0 COMMENT '已售库存',
    safety_stock BIGINT DEFAULT 0 COMMENT '安全库存',
    
    -- ========== 库存限制 ==========
    min_stock BIGINT DEFAULT 0 COMMENT '最小库存（低于此值触发补货提醒）',
    max_stock BIGINT DEFAULT 0 COMMENT '最大库存（独立模式下该市场的最大库存）',
    
    -- ========== 库存状态 ==========
    is_active TINYINT(1) DEFAULT 1 COMMENT '是否启用',
    is_tracked TINYINT(1) DEFAULT 1 COMMENT '是否跟踪库存',
    stock_status VARCHAR(32) DEFAULT 'in_stock' COMMENT '库存状态：in_stock-有货, low_stock-低库存, out_of_stock-缺货',
    
    -- ========== 库存同步 ==========
    last_synced_at TIMESTAMP NULL COMMENT '最后同步时间',
    sync_mode VARCHAR(32) DEFAULT 'auto' COMMENT '同步模式：auto-自动同步, manual-手动同步',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    UNIQUE KEY uk_product_variant_market_store (product_id, variant_id, market, store),
    INDEX idx_product_market (product_id, market),
    INDEX idx_product_market_store (product_id, market, store),
    INDEX idx_variant_market (variant_id, market),
    INDEX idx_stock_status (stock_status),
    
    COMMENT='商品-多市场库存表'
);
```

---

### 4.4 库存数据示例

#### **共享库存模式示例**

| product_id | variant_id | market | store | allocation_mode | total_stock | available_stock | 说明 |
|-----------|-----------|--------|-------|----------------|-------------|----------------|------|
| 1001 | 10001 | * | * | shared | 1000 | 950 | 所有市场、所有门店共享库存 |
| 1001 | 10001 | cn | default | shared | 0 | 0 | 中国市场默认门店使用共享库存（market=*，store=*） |
| 1001 | 10001 | us | default | shared | 0 | 0 | 美国市场默认门店使用共享库存（market=*，store=*） |
| 1001 | 10001 | cn | store_sh01 | shared | 0 | 0 | 中国市场上海门店复用共享库存 |

**库存扣减逻辑：**
- 中国市场售出 50 件 → `market='*'` 的 `available_stock` 减少 50
- 美国市场可用库存自动同步为 950 件

---

#### **独立库存模式示例**

| product_id | variant_id | market | store | allocation_mode | total_stock | available_stock | 说明 |
|-----------|-----------|--------|-------|----------------|-------------|----------------|------|
| 1002 | 10002 | cn | default | independent | 600 | 550 | 中国市场默认门店独立库存 |
| 1002 | 10002 | cn | store_sz01 | independent | 200 | 180 | 中国市场深圳门店独立库存 |
| 1002 | 10002 | us | default | independent | 300 | 300 | 美国市场默认门店独立库存 |
| 1002 | 10002 | us | store_la01 | independent | 120 | 120 | 美国市场洛杉矶门店独立库存 |
| 1002 | 10002 | de | default | independent | 100 | 100 | 德国市场独立库存 |

**库存扣减逻辑：**
- 中国市场售出 50 件 → `market='cn'` 的 `available_stock` 减少 50
- 美国市场可用库存不受影响，仍为 300 件

---

### 4.5 库存扣减流程

```
库存扣减流程：

1. 下单时锁定库存（Lock Stock）
   └─ 将 available_stock 减少，locked_stock 增加
   └─ 设置锁定过期时间（如30分钟）

2. 支付成功后预留库存（Reserve Stock）
   └─ 将 locked_stock 减少，reserved_stock 增加
   └─ 准备发货

3. 发货后扣减库存（Deduct Stock）
   └─ 将 reserved_stock 减少，sold_stock 增加
   └─ 更新 total_stock（如果是共享库存）

4. 订单取消释放库存（Release Stock）
   └─ 将 locked_stock 或 reserved_stock 释放回 available_stock
```

---

### 4.6 库存查询服务

#### **ProductStockService**

```php
<?php
// packages/product/src/Domain/Stock/Services/ProductStockService.php

namespace RedJasmine\Product\Domain\Stock\Services;

use RedJasmine\Product\Domain\Stock\Models\ProductMarketStock;
use RedJasmine\Product\Domain\Stock\Repositories\ProductMarketStockRepositoryInterface;
use RedJasmine\Support\Exceptions\BusinessException;

class ProductStockService
{
    public function __construct(
        protected ProductMarketStockRepositoryInterface $repository
    ) {}
    
    /**
     * 获取商品可用库存
     * 
     * @param int $productId 商品ID
     * @param int|null $variantId SKU ID（可选）
     * @param string $market 市场
     * @return int 可用库存数量
     */
    public function getAvailableStock(
        int $productId,
        ?int $variantId,
        string $market,
        string $store = '*'
    ): int {
        // 1. 查询市场+门店库存记录，优先匹配具体门店
        $marketStock = $this->repository->findByMarketAndStore($productId, $variantId, $market, $store);
        
        // 1.1 若门店未配置，回退到市场默认门店或所有门店
        if (!$marketStock && $store !== 'default') {
            $marketStock = $this->repository->findByMarketAndStore($productId, $variantId, $market, 'default');
        }
        if (!$marketStock) {
            $marketStock = $this->repository->findByMarketAndStore($productId, $variantId, $market, '*');
        }
        
        // 2. 如果是共享库存模式，查询全局共享库存（market='*' 且 store='*'）
        if (!$marketStock || $marketStock->allocation_mode === 'shared') {
            $sharedStock = $this->repository->findByMarketAndStore($productId, $variantId, '*', '*');
            if ($sharedStock) {
                return $sharedStock->available_stock;
            }
        }
        
        // 3. 如果是独立库存模式，返回该市场/门店的可用库存
        if ($marketStock && $marketStock->allocation_mode === 'independent') {
            return $marketStock->available_stock;
        }
        
        // 4. 回退到 product_variants 表的 stock 字段
        return $this->getFallbackStock($productId, $variantId);
    }
    
    /**
     * 锁定库存
     * 
     * @param int $productId 商品ID
     * @param int|null $variantId SKU ID
     * @param string $market 市场
     * @param int $quantity 数量
     * @return bool 是否成功
     */
    public function lockStock(
        int $productId,
        ?int $variantId,
        string $market,
        string $store,
        int $quantity
    ): bool {
        $this->beginDatabaseTransaction();
        
        try {
            // 1. 检查可用库存
            $availableStock = $this->getAvailableStock($productId, $variantId, $market, $store);
            if ($availableStock < $quantity) {
                throw new BusinessException("库存不足，可用库存：{$availableStock}");
            }
            
            // 2. 锁定库存
            $marketStock = $this->getOrCreateMarketStock($productId, $variantId, $market, $store);
            $marketStock->available_stock -= $quantity;
            $marketStock->locked_stock += $quantity;
            $marketStock->save();
            
            $this->commitDatabaseTransaction();
            return true;
        } catch (\Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
    }
    
    /**
     * 释放锁定库存
     */
    public function releaseLockedStock(
        int $productId,
        ?int $variantId,
        string $market,
        string $store,
        int $quantity
    ): bool {
        $marketStock = $this->repository->findByMarketAndStore($productId, $variantId, $market, $store);
        if (!$marketStock) {
            return false;
        }
        
        $releaseQuantity = min($quantity, $marketStock->locked_stock);
        $marketStock->available_stock += $releaseQuantity;
        $marketStock->locked_stock -= $releaseQuantity;
        $marketStock->save();
        
        return true;
    }
    
    /**
     * 扣减库存（发货后）
     */
    public function deductStock(
        int $productId,
        ?int $variantId,
        string $market,
        string $store,
        int $quantity
    ): bool {
        $this->beginDatabaseTransaction();
        
        try {
            $marketStock = $this->repository->findByMarketAndStore($productId, $variantId, $market, $store);
            if (!$marketStock) {
                throw new BusinessException("库存记录不存在");
            }
            
            // 扣减预留库存
            if ($marketStock->reserved_stock < $quantity) {
                throw new BusinessException("预留库存不足");
            }
            
            $marketStock->reserved_stock -= $quantity;
            $marketStock->sold_stock += $quantity;
            
            // 如果是共享库存，更新总库存
            if ($marketStock->allocation_mode === 'shared' && $marketStock->market === '*' && $marketStock->store === '*') {
                $marketStock->total_stock -= $quantity;
            }
            
            $marketStock->save();
            
            $this->commitDatabaseTransaction();
            return true;
        } catch (\Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
    }
    
    /**
     * 分配库存到市场（独立库存模式）
     */
    public function allocateStockToMarket(
        int $productId,
        ?int $variantId,
        string $market,
        string $store,
        int $quantity
    ): bool {
        $this->beginDatabaseTransaction();
        
        try {
            // 1. 检查总库存
            $variant = $this->getVariant($productId, $variantId);
            $totalAllocated = $this->repository->getTotalAllocatedStock($productId, $variantId);
            $availableForAllocation = $variant->stock - $totalAllocated;
            
            if ($availableForAllocation < $quantity) {
                throw new BusinessException(
                    "可分配库存不足，剩余可分配：{$availableForAllocation}"
                );
            }
            
            // 2. 创建或更新市场库存记录
            $marketStock = $this->getOrCreateMarketStock($productId, $variantId, $market, $store);
            $marketStock->allocation_mode = 'independent';
            $marketStock->total_stock += $quantity;
            $marketStock->available_stock += $quantity;
            $marketStock->save();
            
            $this->commitDatabaseTransaction();
            return true;
        } catch (\Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
    }
    
    /**
     * 同步共享库存（自动同步所有市场的可用库存）
     */
    public function syncSharedStock(int $productId, ?int $variantId): void
    {
        $sharedStock = $this->repository->findByMarketAndStore($productId, $variantId, '*', '*');
        if (!$sharedStock) {
            return;
        }
        
        // 更新所有使用共享库存的市场记录（仅更新 available_stock 显示值）
        $marketStocks = $this->repository->findByProductAndVariant($productId, $variantId);
        foreach ($marketStocks as $marketStock) {
            if ($marketStock->allocation_mode === 'shared' && ($marketStock->market !== '*' || $marketStock->store !== '*')) {
                $marketStock->available_stock = $sharedStock->available_stock;
                $marketStock->save();
            }
        }
    }
    
    /**
     * 获取或创建市场库存记录
     */
    protected function getOrCreateMarketStock(
        int $productId,
        ?int $variantId,
        string $market,
        string $store
    ): ProductMarketStock {
        $marketStock = $this->repository->findByMarketAndStore($productId, $variantId, $market, $store);
        
        if (!$marketStock) {
            // 检查是否存在共享库存
            $sharedStock = $this->repository->findByMarketAndStore($productId, $variantId, '*', '*');
            if ($sharedStock) {
                // 使用共享库存
                $marketStock = new ProductMarketStock([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'market' => $market,
                    'store' => $store,
                    'allocation_mode' => 'shared',
                    'total_stock' => 0,
                    'available_stock' => $sharedStock->available_stock,
                ]);
            } else {
                // 创建新的独立库存记录
                $marketStock = new ProductMarketStock([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'market' => $market,
                    'store' => $store,
                    'allocation_mode' => 'independent',
                    'total_stock' => 0,
                    'available_stock' => 0,
                ]);
            }
            $marketStock->save();
        }
        
        return $marketStock;
    }
}
```

---

### 4.7 库存状态管理

#### **库存状态枚举**

```php
<?php
// packages/product/src/Domain/Stock/Models/Enums/StockStatusEnum.php

namespace RedJasmine\Product\Domain\Stock\Models\Enums;

enum StockStatusEnum: string
{
    use EnumsHelper;
    
    case IN_STOCK = 'in_stock';           // 有货
    case LOW_STOCK = 'low_stock';         // 低库存
    case OUT_OF_STOCK = 'out_of_stock';   // 缺货
    case PRE_ORDER = 'pre_order';         // 预售
    
    public static function labels(): array
    {
        return [
            self::IN_STOCK->value => '有货',
            self::LOW_STOCK->value => '低库存',
            self::OUT_OF_STOCK->value => '缺货',
            self::PRE_ORDER->value => '预售',
        ];
    }
    
    /**
     * 根据可用库存计算状态
     */
    public static function calculateStatus(
        int $availableStock,
        int $safetyStock = 0
    ): self {
        if ($availableStock <= 0) {
            return self::OUT_OF_STOCK;
        }
        
        if ($availableStock <= $safetyStock) {
            return self::LOW_STOCK;
        }
        
        return self::IN_STOCK;
    }
}
```

---

### 4.8 库存分配策略配置

#### **商品级别配置**

```php
<?php
// 在 products 表或 products_extension 表中添加字段

$table->string('stock_allocation_mode', 32)
    ->default('shared')
    ->comment('库存分配模式：shared-共享库存, independent-独立库存');

$table->json('market_stock_allocation')
    ->nullable()
    ->comment('市场/门店库存分配配置（独立库存或门店库存模式下使用）');
```

**market_stock_allocation 配置示例：**

```json
{
    "cn": {
        "allocation_mode": "independent",
        "total_stock": 600,
        "min_stock": 50,
        "max_stock": 1000,
        "stores": {
            "store_bj01": {
                "allocation_mode": "independent",
                "total_stock": 200
            },
            "store_sh01": {
                "allocation_mode": "independent",
                "total_stock": 150
            }
        }
    },
    "us": {
        "allocation_mode": "independent",
        "total_stock": 300,
        "min_stock": 30,
        "max_stock": 500
    },
    "*": {
        "allocation_mode": "shared",
        "total_stock": 1000
    }
}
```

---

### 4.9 库存同步机制

#### **自动同步（共享库存模式）**

```php
/**
 * 监听库存变更事件，自动同步共享库存
 */
class SyncSharedStockListener
{
    public function handle(StockChangedEvent $event): void
    {
        $productId = $event->productId;
        $variantId = $event->variantId;
        
        // 如果是共享库存变更，同步所有市场
        $service = app(ProductStockService::class);
        $service->syncSharedStock($productId, $variantId);
    }
}
```

#### **手动同步（独立库存模式）**

```php
/**
 * 手动分配库存到市场
 */
$stockService = app(ProductStockService::class);

// 分配600件库存到中国市场
$stockService->allocateStockToMarket(1001, 10001, 'cn', 600);

// 分配300件库存到美国市场
$stockService->allocateStockToMarket(1001, 10001, 'us', 300);
```

---

### 4.10 库存查询优化

#### **缓存策略**

```php
/**
 * 库存查询缓存（Redis）
 */
class ProductStockCache
{
    protected string $cachePrefix = 'product_stock:';
    protected int $cacheTtl = 300; // 5分钟
    
    public function getAvailableStock(
        int $productId,
        ?int $variantId,
        string $market
    ): ?int {
        $key = $this->getCacheKey($productId, $variantId, $market);
        return Cache::get($key);
    }
    
    public function setAvailableStock(
        int $productId,
        ?int $variantId,
        string $market,
        int $stock
    ): void {
        $key = $this->getCacheKey($productId, $variantId, $market);
        Cache::put($key, $stock, $this->cacheTtl);
    }
    
    public function invalidate(int $productId, ?int $variantId = null): void
    {
        $pattern = $this->cachePrefix . "{$productId}:*";
        if ($variantId) {
            $pattern = $this->cachePrefix . "{$productId}:{$variantId}:*";
        }
        Cache::forget($pattern);
    }
}
```

---

### 4.11 业务场景示例

#### **场景1：跨境电商共享库存**

```
商品：iPhone 15 Pro
总库存：1000件（存放在中国仓库）

配置：
- 中国市场：共享库存
- 美国市场：共享库存
- 德国市场：共享库存

库存记录：
market='*', allocation_mode='shared', total_stock=1000, available_stock=1000

销售情况：
- 中国市场售出：50件 → available_stock=950
- 美国市场可用库存：950件（自动同步）
- 德国市场可用库存：950件（自动同步）
```

---

#### **场景2：多市场独立库存**

```
商品：定制T恤
总库存：1000件

配置：
- 中国市场：独立库存 600件（中国供应商）
- 美国市场：独立库存 300件（美国供应商）
- 德国市场：独立库存 100件（德国供应商）

库存记录：
market='cn', allocation_mode='independent', total_stock=600, available_stock=600
market='us', allocation_mode='independent', total_stock=300, available_stock=300
market='de', allocation_mode='independent', total_stock=100, available_stock=100

销售情况：
- 中国市场售出：50件 → cn市场 available_stock=550
- 美国市场可用库存：300件（不受影响）
- 德国市场可用库存：100件（不受影响）
```

---

#### **场景3：混合模式**

```
商品：限量版球鞋
总库存：500件

配置：
- 中国市场：独立库存 300件（中国仓库）
- 美国+欧洲市场：共享库存 200件（国际仓库）

库存记录：
market='cn', allocation_mode='independent', total_stock=300, available_stock=300
market='*', allocation_mode='shared', total_stock=200, available_stock=200
market='us', allocation_mode='shared', total_stock=0, available_stock=200（显示值）
market='de', allocation_mode='shared', total_stock=0, available_stock=200（显示值）
```

---

### 4.12 设计优势总结

**多市场库存体系的核心优势：**

- ✅ **灵活分配**：支持共享库存、独立库存、混合模式三种策略
- ✅ **精确控制**：可以精确控制每个市场的库存分配
- ✅ **自动同步**：共享库存模式下自动同步，减少管理成本
- ✅ **性能优化**：合理的索引和缓存策略，查询响应快
- ✅ **库存隔离**：独立库存模式下市场间互不影响
- ✅ **易于扩展**：新增市场无需修改代码，只需配置即可
- ✅ **职责清晰**：库存管理与价格管理分离，符合单一职责原则

---

## 五、多语言体系设计

### 5.1 设计原则

```
核心原则：

✅ 主表保留：主表保留默认语言内容（zh-CN）
✅ 专用翻译表：每个实体有对应的 _translations 表
✅ 关联简单：1:N 关系，一次 JOIN 即可获取翻译
✅ 翻译回退：找不到翻译时使用默认语言
✅ 状态管理：支持翻译状态（待翻译、已翻译、已审核）
✅ 批量翻译：支持 AI/API 批量翻译工具
```

---

### 5.2 需要翻译的实体清单

| 实体 | 主表 | 翻译表 | 可翻译字段数 |
|------|------|--------|--------------|
| 商品 | products | product_translations | 9个 |
| 属性 | product_attributes | product_attribute_translations | 4个 |
| 属性值 | product_attribute_values | product_attribute_value_translations | 2个 |
| 属性组 | product_attribute_groups | product_attribute_group_translations | 2个 |
| 类目 | product_categories | product_category_translations | 7个 |
| 品牌 | product_brands | product_brand_translations | 3个 |
| 标签 | product_tags | product_tag_translations | 2个 |

---

### 5.3 翻译表结构示例

```sql
CREATE TABLE product_translations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    locale VARCHAR(10) NOT NULL COMMENT 'zh-CN, en-US, de-DE',
    
    -- 可翻译字段
    title VARCHAR(255) NOT NULL COMMENT '标题',
    slogan VARCHAR(255) NULL COMMENT '广告语',
    short_description TEXT NULL,
    description LONGTEXT NULL,
    
    -- SEO
    meta_title VARCHAR(255) NULL,
    meta_keywords VARCHAR(255) NULL,
    meta_description TEXT NULL,
    
    url_slug VARCHAR(255) NULL,
    available_text VARCHAR(255) NULL,
    unavailable_text VARCHAR(255) NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_product_locale (product_id, locale),
    
    COMMENT='商品-翻译表'
);
```

---

## 六、数据库表设计清单

### 6.1 需要修改的现有表

| 表名 | 操作 | 新增字段 |
|------|------|----------|
| `products` | 修改 | platform, biz |
| `product_variants` | 修改 | platform, biz |
| `product_attributes` | 修改 | 移除 name 字段 |

### 6.2 需要新建的表

#### **多价格表（2个）**
- `product_prices` - 商品多维度价格表
- `exchange_rates` - 汇率表（可选）

#### **多市场库存表（1个）**
- `product_stocks` - 商品多市场库存表

#### **多语言翻译表（7个）**
- `product_translations` - 商品翻译表
- `product_attribute_translations` - 属性翻译表
- `product_attribute_value_translations` - 属性值翻译表
- `product_attribute_group_translations` - 属性组翻译表
- `product_category_translations` - 类目翻译表
- `product_brand_translations` - 品牌翻译表
- `product_tag_translations` - 标签翻译表

---

## 七、详细表结构设计

### 7.1 products 表修改

```php
// 在 owner_id 字段后添加三维度字段

$table->string('market', 64)
    ->default('default')
    ->comment('市场站点：cn-中国, us-美国, de-德国');

$table->string('platform', 64)
    ->default('default')
    ->comment('业务平台：mall-商城, low_price-低价商城, luxury-高端商城, secondhand-二手平台, takeaway-外卖, dine_in-点餐');

$table->string('biz', 64)
    ->default('default')
    ->comment('业务线：self-自营, pop-POP, franchise-加盟');

// 索引
$table->index(['market', 'platform', 'biz'], 'idx_dimensions');
```

---

## 八、抽象共享概念设计

### 8.1 跨领域统一概念：Partition（分区）

#### **设计背景**

不同领域对"站点"的称呼不同：
- 商品领域：market（市场）
- 文章领域：site（站点）
- 用户领域：region（地区）

为了统一管理配置，底层抽象出 **Partition（分区）** 概念。

---

#### **8.1.1 HasPartition Trait**

```php
<?php
// packages/support/src/Domain/Models/Traits/HasPartition.php

namespace RedJasmine\Support\Domain\Models\Traits;

/**
 * 分区特性
 * 
 * 为不同领域的"站点"概念提供统一的底层支持
 * 各领域可以用自己的业务术语，但底层映射到统一的 partition
 */
trait HasPartition
{
    /**
     * 获取分区标识
     * 子类覆盖此方法来映射自己的字段
     */
    public function getPartition(): string
    {
        return $this->{$this->getPartitionColumn()} ?? 'default';
    }
    
    /**
     * 设置分区
     */
    public function setPartition(string $partition): self
    {
        $this->{$this->getPartitionColumn()} = $partition;
        return $this;
    }
    
    /**
     * 查询作用域：按分区查询
     */
    public function scopePartition($query, string $partition)
    {
        return $query->where($this->getPartitionColumn(), $partition);
    }
    
    /**
     * 获取分区字段名（子类覆盖）
     */
    protected function getPartitionColumn(): string
    {
        // 默认字段名
        return 'partition';
    }
    
    /**
     * 获取分区配置
     */
    public function getPartitionConfig(string $key = null)
    {
        $service = app(\RedJasmine\Support\Application\Services\PartitionService::class);
        $config = $service->getConfig($this->getPartition());
        
        return $key ? ($config[$key] ?? null) : $config;
    }
}
```

---

#### **8.1.2 PartitionService**

```php
<?php
// packages/support/src/Application/Services/PartitionService.php

namespace RedJasmine\Support\Application\Services;

/**
 * 分区服务
 * 
 * 提供分区相关的通用功能
 */
class PartitionService
{
    /**
     * 获取分区配置
     */
    public function getConfig(string $partition): array
    {
        return config("partitions.{$partition}", []);
    }
    
    /**
     * 获取分区的货币
     */
    public function getCurrency(string $partition): string
    {
        return $this->getConfig($partition)['currency']['code'] ?? 'CNY';
    }
    
    /**
     * 获取分区的语言
     */
    public function getLocale(string $partition): string
    {
        return $this->getConfig($partition)['locale']['code'] ?? 'zh-CN';
    }
    
    /**
     * 获取分区的时区
     */
    public function getTimezone(string $partition): string
    {
        return $this->getConfig($partition)['timezone'] ?? 'Asia/Shanghai';
    }
    
    /**
     * 检查业务平台是否支持该市场
     */
    public function isMarketSupportedByPlatform(
        string $platform, 
        string $market
    ): bool {
        $supportedMarkets = config(
            "platforms.{$platform}.supported_markets", 
            []
        );
        
        return in_array($market, $supportedMarkets);
    }
    
    /**
     * 获取业务平台支持的市场列表
     */
    public function getSupportedMarkets(string $platform): array
    {
        return config(
            "platforms.{$platform}.supported_markets", 
            ['default']
        );
    }
}
```

---

#### **8.1.3 统一配置文件**

```php
<?php
// config/partitions.php

return [
    /*
    |--------------------------------------------------------------------------
    | 分区配置
    |--------------------------------------------------------------------------
    |
    | 统一管理所有分区（站点/市场/地区）的配置
    | 不同领域可以用自己的术语，但配置在这里统一管理
    |
    */
    
    'cn' => [
        'name' => '中国',
        'code' => 'cn',
        
        // 货币配置
        'currency' => [
            'code' => 'CNY',
            'symbol' => '¥',
            'decimal_places' => 2,
        ],
        
        // 语言配置
        'locale' => [
            'code' => 'zh-CN',
            'name' => '简体中文',
            'direction' => 'ltr',
        ],
        
        // 时区
        'timezone' => 'Asia/Shanghai',
        
        // 领域特定配置
        'product' => [
            'tax_rate' => 0.13,
            'shipping_days' => [1, 3],
        ],
        
        'article' => [
            'default_template' => 'zh-template',
            'seo_title_suffix' => ' - 中文站',
        ],
    ],
    
    'us' => [
        'name' => '美国',
        'code' => 'us',
        
        'currency' => [
            'code' => 'USD',
            'symbol' => '$',
            'decimal_places' => 2,
        ],
        
        'locale' => [
            'code' => 'en-US',
            'name' => 'English',
            'direction' => 'ltr',
        ],
        
        'timezone' => 'America/New_York',
        
        'product' => [
            'tax_rate' => 0.08,
            'shipping_days' => [5, 10],
        ],
        
        'article' => [
            'default_template' => 'en-template',
            'seo_title_suffix' => ' - US Site',
        ],
    ],
];
```

---

#### **8.1.4 领域中的使用**

**商品领域：使用 market**

```php
<?php
// packages/product/src/Domain/Product/Models/Product.php

class Product extends Model
{
    use HasPartition;
    
    // 映射到 market 字段
    protected function getPartitionColumn(): string
    {
        return 'market';
    }
    
    // 业务方法：获取市场货币
    public function getMarketCurrency(): string
    {
        return $this->getPartitionConfig('currency')['code'] ?? 'CNY';
    }
}
```

**文章领域：使用 site**

```php
<?php
// packages/article/src/Domain/Article/Models/Article.php

class Article extends Model
{
    use HasPartition;
    
    // 映射到 site 字段
    protected function getPartitionColumn(): string
    {
        return 'site';
    }
    
    // 业务方法：获取站点语言
    public function getSiteLocale(): string
    {
        return $this->getPartitionConfig('locale')['code'] ?? 'zh-CN';
    }
}
```

---

### 8.2 业务平台与市场验证

#### **ProductValidator**

```php
<?php
// packages/product/src/Domain/Product/Services/ProductValidator.php

namespace RedJasmine\Product\Domain\Product\Services;

use RedJasmine\Support\Application\Services\PartitionService;
use RedJasmine\Support\Exceptions\BusinessException;

class ProductValidator
{
    public function __construct(
        protected PartitionService $partitionService
    ) {}
    
    /**
     * 验证市场是否被业务平台支持
     */
    public function validateMarketForPlatform(
        string $platform, 
        string $market
    ): void {
        if (!$this->partitionService->isMarketSupportedByPlatform(
            $platform, 
            $market
        )) {
            $supportedMarkets = $this->partitionService
                ->getSupportedMarkets($platform);
            
            throw new BusinessException(
                "业务平台 {$platform} 不支持市场 {$market}，" .
                "支持的市场：" . implode(', ', $supportedMarkets)
            );
        }
    }
}
```

**使用示例：**

```php
// 创建商品时验证
$validator = app(ProductValidator::class);

try {
    // ✅ 正确：mall 业务平台支持 us 市场
    $validator->validateMarketForPlatform('mall', 'us');
    
    // ❌ 错误：takeaway 业务平台不支持 us 市场
    $validator->validateMarketForPlatform('takeaway', 'us');
    // 抛出异常：业务平台 takeaway 不支持市场 us，支持的市场：cn
    
} catch (BusinessException $e) {
    // 处理异常
}
```

---

## 九、核心代码组件

### 9.1 Trait: HasTranslations

**文件位置：** `packages/support/src/Domain/Models/Traits/HasTranslations.php`

**核心方法：**

| 方法 | 说明 |
|------|------|
| `translations()` | 翻译关联（HasMany） |
| `translate(?string $locale)` | 获取指定语言翻译 |
| `getTranslations()` | 获取所有语言翻译 |
| `setTranslation()` | 设置翻译 |
| `scopeWithTranslation()` | 查询作用域 |

---

### 9.2 Service: ProductPriceService

**文件位置：** `packages/product/src/Domain/Product/Services/ProductPriceService.php`

**核心方法：**

| 方法 | 说明 |
|------|------|
| `getPrice()` | 获取商品价格 |
| `calculateTierPrice()` | 计算阶梯价格 |
| `convertCurrency()` | 货币转换 |

---

### 9.3 枚举类

#### **PlatformEnum**

```php
enum PlatformEnum: string
{
    use EnumsHelper;
    
    case DEFAULT = 'default';
    case MALL = 'mall';
    case LOW_PRICE = 'low_price';
    case LUXURY = 'luxury';
    case SECONDHAND = 'secondhand';
    case TAKEAWAY = 'takeaway';
    case DINE_IN = 'dine_in';
    case TRAVEL = 'travel';
    case HOTEL = 'hotel';
    case TICKET = 'ticket';
    case B2B = 'b2b';
    
    public static function labels(): array
    {
        return [
            self::DEFAULT->value => '默认业务平台',
            self::MALL->value => '商城业务平台',
            self::LOW_PRICE->value => '低价商城业务平台',
            self::LUXURY->value => '高端商城业务平台',
            self::SECONDHAND->value => '二手业务平台',
            self::TAKEAWAY->value => '外卖业务平台',
            self::DINE_IN->value => '堂食业务平台',
            self::TRAVEL->value => '旅游业务平台',
            self::HOTEL->value => '酒店业务平台',
            self::TICKET->value => '门票业务平台',
            self::B2B->value => '企业采购业务平台',
        ];
    }
}
```

#### **MarketEnum**

```php
enum MarketEnum: string
{
    case DEFAULT = 'default';
    case CN = 'cn';
    case US = 'us';
    case UK = 'uk';
    case DE = 'de';
    case FR = 'fr';
    case JP = 'jp';
}
```

#### **BizEnum**

```php
enum BizEnum: string
{
    case DEFAULT = 'default';
    case SELF = 'self';
    case POP = 'pop';
    case FRANCHISE = 'franchise';
    case WHOLESALE = 'wholesale';
}
```

---

## 十、实施路线图

### Phase 1: 基础三维度（Week 1-2）⭐⭐⭐⭐⭐

**目标：** 搭建三维度基础架构

**任务清单：**
1. 修改 products 表添加三维度字段
2. 创建三个枚举类
3. 修改 Product Model
4. 修改 orders 表
5. 单元测试

**验收标准：**
- ✅ 数据库字段添加成功
- ✅ 单元测试覆盖率 >80%

---

### Phase 2: 多价格体系（Week 3-4）⭐⭐⭐⭐

**目标：** 实现多维度价格管理

**任务清单：**
1. 创建 product_prices 表
2. 创建 ProductPrice Model
3. 实现 ProductPriceService
4. 价格匹配算法
5. 单元测试和性能测试

**验收标准：**
- ✅ 价格查询准确率 100%
- ✅ 查询响应时间 <100ms

---

### Phase 3: 多语言体系（Week 5-6）⭐⭐⭐⭐⭐

**目标：** 实现完整多语言翻译体系

**任务清单：**
1. 创建 HasTranslations Trait
2. 创建所有翻译表
3. 修改所有 Model
4. API 接口调整
5. 单元测试

**验收标准：**
- ✅ 所有实体支持多语言
- ✅ 翻译回退正常工作

---

### Phase 4: 翻译工具和优化（Week 7-8）⭐⭐⭐

**目标：** 翻译管理工具和系统优化

**任务清单：**
1. TranslationService 实现
2. 集成 AI 翻译
3. Filament 管理后台
4. 性能优化
5. 文档编写

**验收标准：**
- ✅ AI 翻译准确率 >90%
- ✅ 完整的使用文档

---

## 十一、技术栈说明

### 11.1 核心依赖

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "spatie/laravel-data": "^4.0",
        "spatie/laravel-query-builder": "^5.0"
    }
}
```

### 11.2 可选依赖

```json
{
    "require-dev": {
        "openai-php/laravel": "^0.8",
        "deepl-php/deepl": "^1.0",
        "predis/predis": "^2.0"
    }
}
```

---

## 十二、预期收益

### 12.1 业务价值

| 价值点 | 说明 | 量化指标 |
|--------|------|----------|
| **多业务平台支持** | 一套系统支持多种业务平台 | 支持 10+ 业务平台 |
| **跨境电商** | 快速拓展海外市场 | 支持 20+ 国家 |
| **灵活定价** | 多维度定价策略 | 支持 3 个价格维度（market、store、user_level），渠道差异化依旧通过促销系统实现 |
| **多市场库存** | 灵活的库存分配策略 | 支持共享库存、独立库存、混合模式三种策略 |
| **多语言** | 完整的国际化支持 | 支持 10+ 语言 |

### 12.2 技术价值

- ✅ 架构清晰，易于维护
- ✅ 扩展性强，可快速适配新业务平台
- ✅ 性能优化，支持大规模数据
- ✅ 符合 DDD 架构原则
- ✅ 代码复用性高

---

## 十三、风险评估

### 13.1 技术风险

| 风险项 | 等级 | 应对措施 |
|--------|------|----------|
| **数据迁移复杂** | 中 | 分阶段实施，保留向后兼容 |
| **查询性能下降** | 中 | 合理索引、缓存策略 |
| **翻译准确性** | 低 | 人工审核、质量评分 |

### 13.2 业务风险

| 风险项 | 等级 | 应对措施 |
|--------|------|----------|
| **业务复杂度增加** | 中 | 完善文档、培训支持 |
| **学习成本** | 低 | 提供最佳实践 |

---

## 十四、方案总结

### 14.1 核心亮点

```
┌─────────────────────────────────────────────────────────┐
│                    核心优势总结                          │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  🎯 层级设计：platform → market → biz                  │
│     业务平台决定市场范围，层级清晰，约束合理             │
│                                                          │
│  💰 多价格：支持市场、会员、阶梯、促销等多维度定价      │
│     一个商品在不同维度组合下有不同价格                   │
│                                                          │
│  📦 多库存：支持共享库存、独立库存、混合模式            │
│     灵活分配各市场库存，自动同步或精确控制               │
│                                                          │
│  🌐 国际化：完整的多语言翻译体系，支持 AI 翻译          │
│     主表+翻译表，查询高效，回退机制完善                  │
│                                                          │
│  🔗 抽象统一：Partition 概念统一管理站点配置            │
│     不同领域用自己的术语，底层统一管理                   │
│                                                          │
│  🚀 性能好：合理的索引和缓存策略，响应<100ms            │
│     多维度复合索引、Redis 缓存、扁平化表                │
│                                                          │
│  📐 架构清：清晰的分层设计，符合 DDD 原则               │
│     限界上下文清晰，通用语言准确，职责分明               │
│                                                          │
│  🔧 易扩展：新增业务平台/市场/语言无需修改核心代码      │
│     配置驱动，枚举扩展，数据库兼容                       │
│                                                          │
│  ✅ 可验证：业务平台-市场约束自动验证                   │
│     防止创建不合法的商品组合                             │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

**关键设计说明：**

1. **层级关系**：业务平台（platform）决定可用的 market 范围
   - mall 业务平台：支持全球市场（cn, us, uk, de...）
   - low_price 业务平台：主要面向中国市场（cn）
   - takeaway 业务平台：仅支持中国市场（cn）
   - 通过配置文件和验证器保证数据一致性

2. **抽象共享**：Partition 概念统一不同领域
   - 商品领域：market（市场）
   - 文章领域：site（站点）
   - 用户领域：region（地区）
   - 底层统一配置：config/partitions.php

3. **多维定价**：支持 2 个维度组合定价
   - market + user_level
   - 业务平台（platform）不在价格维度中，因为商品本身已有 platform 字段
   - 业务线（biz）不在价格维度中，价格体系与业务线无关
   - 渠道（channel）不在价格维度中，渠道差异化定价通过促销系统实现
   - 通配符支持：* 表示所有
   - 优先级匹配：精确匹配优先

4. **多市场库存**：支持 3 种库存分配策略
   - 共享库存模式：所有市场共享同一份库存，自动同步
   - 独立库存模式：每个市场独立库存分配，互不影响
   - 混合模式：部分市场共享，部分市场独立
   - 库存维度：market（市场）
   - 库存状态：有货、低库存、缺货、预售
   - 库存扣减：锁定 → 预留 → 扣减的完整流程

5. **多语言**：主表+翻译表模式
   - 查询效率高（一次 JOIN）
   - 翻译回退机制
   - 支持 AI 批量翻译

### 14.2 适用场景

- ✅ 跨境电商平台
- ✅ 多业务平台电商系统
- ✅ SaaS 多租户电商系统
- ✅ 企业级商品中心
- ✅ 餐饮点餐/外卖系统（支持多平台、多门店差异化定价与库存）

---

## 附录

### 附录A：数据库表 SQL 脚本

详见单独文件：`database-schema.sql`

### 附录B：API 接口设计文档

详见单独文件：`api-documentation.md`

### 附录C：性能测试报告

详见单独文件：`performance-test-report.md`

### 附录D：数据迁移指南

详见单独文件：`migration-guide.md`

---

## 文档变更记录

| 版本 | 日期 | 作者 | 变更说明 |
|------|------|------|----------|
| v1.0 | 2024-11-13 | Red Jasmine Team | 初始版本 |
| v1.1 | 2024-11-13 | Red Jasmine Team | 将 business_type 替换为 platform |

---

## 审批记录

| 角色 | 姓名 | 审批意见 | 日期 | 签字 |
|------|------|----------|------|------|
| 技术负责人 | | ☐ 同意 ☐ 不同意 | | |
| 产品负责人 | | ☐ 同意 ☐ 不同意 | | |
| 项目经理 | | ☐ 同意 ☐ 不同意 | | |

---

**文档状态：** 📝 待审批

**© 2024 Red Jasmine Framework. All Rights Reserved.**

