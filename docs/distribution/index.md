---
title: 分销系统
outline: deep
order: 5
---

# 分销系统

## 概述

分销系统是一个基于DDD（领域驱动设计）架构的电商分销业务模块，通过分销员推广商品获得佣金的方式，构建多层级的分销网络。系统支持分销员招募、等级管理、团队管理、佣金结算等核心功能。

### 核心业务特点

- **多层级推广关系**：支持分销员上下级关系建立，形成推广树结构
- **灵活的等级体系**：支持多等级分销员管理，不同等级享有不同权益
- **智能化审核机制**：支持手动、自动等多种审核方式
- **完整的团队管理**：支持团队组建、成员管理、业绩统计
- **精准的佣金结算**：与订单、支付系统深度集成，确保佣金准确结算

## 领域参与角色

### 内部角色
- **分销员**：具有推广资格的用户，可推广商品获得佣金
- **管理员**：负责分销员审核、等级管理、团队管理等运营工作
- **系统审核员**：负责分销员申请的审核处理

### 外部角色
- **用户**：通过分销员推广链接注册、下单的最终消费者
- **商家**：提供商品和设置佣金比例的商品提供方

## 连接领域

- **用户领域**：用户注册、身份验证、基础信息管理
- **商品领域**：商品信息、价格、库存、佣金设置
- **订单领域**：订单创建、状态变更、完成确认
- **支付领域**：佣金结算、提现处理、财务对账
- **钱包领域**：佣金账户、余额管理、交易记录

## 核心用例

### 1. 分销员招募与管理

#### 分销员申请流程
1. **申请条件设置**：管理员配置不同等级的申请条件
2. **用户申请**：符合条件的用户提交分销员申请
3. **审核处理**：支持自动审核和人工审核两种方式
4. **结果通知**：申请结果通过事件通知相关方

```plantuml
<!--@include: ./申请流程.puml-->
```

#### 分销员等级管理
- **等级晋升**：根据业绩自动或手动晋升等级
- **等级权益**：不同等级享有不同的佣金比例和权益
- **保级机制**：设置保级条件，防止等级下降

### 2. 推广关系建立

#### 邀请码机制
- **唯一邀请码**：每个分销员拥有唯一的邀请码
- **关系绑定**：用户通过邀请码注册后自动建立推广关系
- **有效期管理**：支持设置关系有效期和保护期

#### 推广链接与海报
- **动态链接生成**：支持商品推广链接和注册推广链接
- **海报生成**：自动生成包含分销员信息的推广海报
- **二维码支持**：生成带参数的二维码，便于移动端推广

### 3. 团队管理

#### 团队组建
- **团队创建**：支持创建推广团队，设置团队负责人
- **成员管理**：团队成员的加入、退出管理
- **层级管理**：支持多层级团队结构

#### 业绩统计
- **团队业绩**：统计团队整体销售业绩和佣金
- **个人业绩**：记录每个成员的推广业绩
- **排行榜**：支持团队和个人业绩排行

### 4. 佣金结算

#### 佣金计算
- **多级分佣**：支持多层级佣金分配
- **实时计算**：订单确认后实时计算佣金
- **冻结机制**：新产生的佣金先冻结，订单完成后解冻

#### 提现管理
- **提现申请**：分销员可申请佣金提现
- **手续费计算**：支持设置提现手续费和个税
- **审核流程**：提现申请需经过审核才能打款

```plantuml
<!--@include: ./core_flow.puml-->
```

## 统一语言表

| 英文名称 | 中文名称 | 说明 |
|---------|---------|------|
| Promoter | 分销员 | 具备推广资格的用户，可以通过推广获得佣金 |
| PromoterLevel | 分销员等级 | 不同等级的分销员享有不同的权益和佣金比例 |
| PromoterApply | 分销员申请 | 用户申请成为分销员或升级等级的申请单 |
| PromoterGroup | 分销员分组 | 用于对分销员进行分类管理 |
| PromoterTeam | 分销员团队 | 分销员组成的推广团队 |
| PromoterBindUser | 推广关系 | 分销员与用户之间的推广绑定关系 |
| PromoterOrder | 推广订单 | 通过分销员推广产生的订单记录 |
| InvitationCode | 邀请码 | 分销员的唯一邀请标识 |
| Commission | 佣金 | 分销员通过推广获得的收入 |
| ApplyMethod | 申请方式 | 分销员申请的方式：关闭/手动/自动 |
| AuditMethod | 审核方式 | 申请审核的方式：手动/自动 |
| ApplyType | 申请类型 | 申请的类型：注册/升级/降级 |

## 领域模型

```plantuml
<!--@include: ./distribution.puml-->
```

## 领域事件

### 分销员相关事件
- **PromoterApplied**：分销员申请事件
- **PromoterUpgraded**：分销员升级事件  
- **PromoterDowngraded**：分销员降级事件
- **PromoterEnabled**：分销员启用事件
- **PromoterDisabled**：分销员禁用事件
- **PromoterDeleted**：分销员删除事件

### 申请审核事件
- **PromoterApplyApproved**：分销员申请审核通过事件
- **PromoterApplyRejected**：分销员申请审核拒绝事件

### 团队管理事件
- **TeamCreated**：团队创建事件
- **MemberJoined**：成员加入团队事件
- **MemberLeft**：成员离开团队事件

### 推广关系事件
- **RelationshipEstablished**：推广关系建立事件
- **RelationshipExpired**：推广关系过期事件

## 核心规则

### 1. 分销员等级规则
- 分销员等级从0开始，0级为申请状态
- 等级越高，享有的佣金比例越高
- 升级需满足指定条件（销售额、推广人数等）
- 支持保级机制，防止恶意刷单后降级

### 2. 推广关系规则
- 每个用户只能被一个分销员推广（绑客模式）
- 推广关系具有有效期和保护期
- 保护期内用户产生的订单都归属于推广分销员
- 关系过期后可重新建立推广关系

### 3. 佣金结算规则
- 佣金按照订单确认时的分销员等级计算
- 新产生佣金先冻结，订单完成后解冻到可用余额
- 支持多级分佣，上级分销员也可获得下级推广佣金
- 退单时需相应扣减已发放的佣金

### 4. 团队管理规则
- 团队负责人可管理团队成员
- 团队业绩为所有成员业绩之和
- 成员离开团队后，历史业绩不受影响
- 支持团队层级，可设置上级管理团队

## 存储层设计

### 核心数据表

#### promoters（分销员表）
```sql
CREATE TABLE `promoters` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID',
  `owner_type` varchar(32) NOT NULL COMMENT '所属人类型',
  `owner_id` varchar(64) NOT NULL COMMENT '所属人ID', 
  `level` tinyint(3) UNSIGNED DEFAULT 0 COMMENT '等级',
  `group_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '所属分组ID',
  `parent_id` bigint(20) UNSIGNED DEFAULT 0 COMMENT '所属上级ID',
  `status` varchar(32) NOT NULL COMMENT '状态',
  `team_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '所属团队ID',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `idx_owner` (`owner_type`, `owner_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_status` (`status`)
) COMMENT='分销员';
```

#### promoter_applies（分销员申请表）
```sql
CREATE TABLE `promoter_applies` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID',
  `promoter_id` bigint(20) UNSIGNED NOT NULL COMMENT '分销员ID',
  `level` tinyint(3) UNSIGNED NOT NULL COMMENT '申请等级',
  `apply_type` varchar(32) NOT NULL COMMENT '申请类型',
  `apply_method` varchar(32) NOT NULL COMMENT '申请方式', 
  `approval_method` varchar(32) NOT NULL COMMENT '审核方式',
  `approval_status` varchar(32) NOT NULL COMMENT '审核状态',
  `apply_at` datetime NOT NULL COMMENT '申请时间',
  `approval_at` datetime DEFAULT NULL COMMENT '审核时间',
  `approval_reason` text COMMENT '审核理由',
  PRIMARY KEY (`id`),
  KEY `idx_promoter` (`promoter_id`),
  KEY `idx_status` (`approval_status`)
) COMMENT='分销员申请';
```

#### promoter_levels（分销员等级表）
- 存储不同等级的配置信息
- 包括升级条件、保级条件、权益配置等

#### promoter_bind_users（推广关系表）  
- 记录分销员与用户的推广绑定关系
- 包括关系状态、有效期、保护期等信息

#### promoter_teams（分销员团队表）
- 团队基础信息和统计数据
- 支持团队层级结构

#### promoter_groups（分销员分组表）
- 用于分销员的分类管理
- 便于运营人员进行精细化管理

## 技术架构

### DDD分层架构

#### 领域层（Domain）
- **Models**：核心领域模型（Promoter、PromoterApply等）
- **Events**：领域事件定义
- **Repositories**：仓储接口定义
- **Services**：领域服务
- **ValueObjects**：值对象
- **Enums**：枚举类型

#### 应用层（Application）
- **Services**：应用服务（PromoterApplicationService等）
- **Commands**：命令对象
- **Queries**：查询对象
- **Handlers**：命令和查询处理器

#### 基础设施层（Infrastructure）
- **Repositories**：仓储实现
- **External**：外部服务集成

#### 用户界面层（UI）
- **Http**：HTTP API控制器
- **Resources**：API资源转换
- **Requests**：请求验证

### 集成点

#### 事件驱动
- 通过领域事件实现模块间解耦
- 异步处理佣金结算、消息通知等

#### 仓储模式
- 通过仓储接口隔离数据访问
- 支持多种数据存储方式

#### 依赖注入
- 通过Laravel的服务容器管理依赖
- 便于测试和扩展

## 扩展说明

### 佣金策略扩展
- 支持多种佣金计算策略
- 可根据商品类型、分销员等级动态调整

### 审核流程扩展  
- 支持多级审核流程
- 可配置不同场景的审核规则

### 统计分析扩展
- 支持多维度数据统计
- 可扩展报表分析功能

### 营销活动扩展
- 支持限时推广活动
- 可配置特殊佣金规则
