---
title: 邀请领域
description: 邀请码和邀请链接管理系统
outline: deep
order: 1
---

# 邀请领域

## 概述

邀请领域是Red Jasmine框架中负责处理用户邀请机制的核心模块。它提供了灵活、安全、可扩展的邀请系统，支持邀请码生成、管理和使用，以及可配置的邀请链接功能。

### 问题域

邀请领域要解决的核心问题包括：

1. **邀请码管理**：如何生成唯一的邀请码，支持自定义和系统生成两种方式
2. **使用控制**：如何控制邀请码的使用次数和有效期限制
3. **邀请链接**：如何生成可配置跳转目标的邀请链接
4. **邀请追踪**：如何记录和追踪邀请关系和效果
5. **防止滥用**：如何防止邀请码被恶意使用和滥用

### 业务价值

- **用户增长**：通过邀请机制促进用户增长和裂变
- **精确追踪**：提供详细的邀请数据分析和效果追踪
- **灵活配置**：支持多种邀请场景和业务需求
- **安全可靠**：确保邀请码的唯一性和使用安全性
- **奖励机制**：支持邀请奖励和激励体系

## 核心能力

### 1. 邀请码管理
- 支持自定义邀请码和系统生成邀请码
- 邀请码唯一性保证，生成后不可修改
- 支持邀请码状态管理（激活、禁用、过期、用尽）
- 提供邀请码批量生成功能

### 2. 使用控制
- 支持设置最大使用次数限制
- 支持设置过期时间限制
- 自动状态更新和使用次数统计
- 使用频率限制防止滥用

### 3. 邀请链接
- 支持生成可配置跳转目标的邀请链接
- 支持注册页、商品页、自定义页面跳转
- 链接签名验证确保安全性
- 支持链接参数自定义

### 4. 邀请记录
- 完整记录邀请关系和使用情况
- 支持邀请上下文信息存储
- 提供邀请完成状态管理
- 支持邀请奖励信息记录

### 5. 统计分析
- 邀请效果统计和分析
- 邀请转化率计算
- 邀请人排行榜功能
- 邀请数据报表生成

## 领域参与角色

### 内部角色

- **邀请人（Inviter）**：创建和分享邀请码的用户
- **被邀请人（Invitee）**：使用邀请码的用户
- **管理员（Admin）**：管理邀请码和配置的系统管理人员
- **系统（System）**：自动处理邀请逻辑的系统组件

### 外部角色

- **业务系统**：集成邀请功能的外部业务系统
- **奖励系统**：处理邀请奖励的外部系统
- **通知系统**：发送邀请通知的外部系统

## 连接领域

### 上游领域

- **用户领域**：提供用户信息和身份验证
- **权限领域**：提供权限验证和访问控制

### 下游领域

- **奖励领域**：处理邀请奖励和激励
- **通知领域**：发送邀请相关通知
- **统计领域**：处理邀请数据统计分析

## 核心用例

### UC001 - 创建邀请码

**参与者**：邀请人

**前置条件**：
- 用户已登录系统
- 用户有创建邀请码权限

**主流程**：
1. 用户选择邀请码类型（自定义/系统生成）
2. 系统验证邀请码唯一性（如果是自定义）
3. 用户设置使用限制（次数、过期时间）
4. 用户选择跳转目标类型
5. 系统生成邀请码记录
6. 返回邀请码信息

**后置条件**：
- 邀请码已创建并可使用
- 邀请码信息已持久化

### UC002 - 使用邀请码

**参与者**：被邀请人

**前置条件**：
- 拥有有效的邀请码
- 邀请码未达到使用限制

**主流程**：
1. 用户提交使用邀请码请求
2. 系统验证邀请码有效性
3. 系统检查使用次数和过期时间
4. 系统创建邀请记录
5. 系统更新邀请码使用次数
6. 系统触发邀请完成事件
7. 返回使用结果

**后置条件**：
- 邀请关系已建立
- 邀请码使用次数已更新

### UC003 - 生成邀请链接

**参与者**：邀请人

**前置条件**：
- 邀请码已存在且有效
- 用户有生成链接权限

**主流程**：
1. 用户提交生成邀请链接请求
2. 系统验证邀请码存在性
3. 系统确定跳转目标URL
4. 系统构建包含邀请码的完整链接
5. 系统添加安全签名（可选）
6. 返回邀请链接

**后置条件**：
- 邀请链接已生成
- 链接包含必要的邀请参数

## 统一语言表

| 英文名称 | 中文名称 | 说明 | 示例 |
|---------|---------|------|------|
| InvitationCode | 邀请码 | 用于邀请他人的唯一标识码 | INVITE123 |
| Inviter | 邀请人 | 创建和分享邀请码的用户 | User{id: 1001} |
| Invitee | 被邀请人 | 使用邀请码的用户 | User{id: 1002} |
| MaxUsage | 最大使用次数 | 邀请码可使用的最大次数 | 100 |
| UsedCount | 已使用次数 | 邀请码已被使用的次数 | 25 |
| ExpiredAt | 过期时间 | 邀请码失效的时间点 | 2024-12-31 23:59:59 |
| TargetType | 跳转目标类型 | 邀请链接的跳转目标类型 | register, product, custom |
| TargetUrl | 跳转目标URL | 自定义跳转的具体URL地址 | https://example.com/register |
| InvitationRecord | 邀请记录 | 记录邀请关系和使用情况 | Record{id: 5001} |
| InvitationUrl | 邀请链接 | 包含邀请码的完整链接 | https://app.com/register?code=INVITE123 |
| CodeStatus | 邀请码状态 | 邀请码的当前状态 | active, disabled, expired |
| Context | 邀请上下文 | 邀请使用时的环境信息 | {source: "wechat", device: "mobile"} |
| Signature | 链接签名 | 用于验证邀请链接安全性的签名 | sha256hash |

## 领域模型

### 核心实体

- **邀请码（InvitationCode）**：邀请系统的核心实体，管理邀请码的生命周期
- **邀请记录（InvitationRecord）**：记录邀请关系和使用情况的实体

### 值对象

- **邀请码配置（InvitationCodeConfig）**：封装邀请码生成和配置的值对象

### 枚举

- **邀请码类型枚举**：系统生成 、 自定义
- **邀请码状态枚举**：激活、禁用、过期、用尽
- **邀请目标类型枚举**：注册页、商品页、自定义页面

详细的技术实现方案请参考：[技术方案文档](./技术方案.md) 