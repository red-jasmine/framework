---
title: 文章
outline: deep
order: 3
---

# 文章

## 领域分析

### 用例分析

- 用户 发布 文章
- 用户 修改 文章
- 管理员 审核 文章
- 管理员 删除 文章

### 核心流程

## 领域模型

```plantuml
@startuml

class Article<<文章>> {
+ id:Int
+ title-标题:String 
+ image-图片:String 
+ content-type-内容类型: String
+ content-内容: String
+ tags-标签: Array<String>
+ status-状态: Enum
+ category-分类: Category
+ owner-作者: User
+ approvalStatus 审核: Enum
- createdAt-创建时间: Date
- updatedAt-更新时间: Date


+ save-保存(Article: article): Article
+ review-审核(Int: id): boolean
+ publish-发布(Int: id): boolean

}

class Category<<分类>> {
+ id:Int
+ parent-父级: Category
+ name-名称: String
+ description-描述: String
+ icon-图标: ?String
+ image-图片: ?String
+ status-状态: Enum
+ sort-排序: Int
}

class Tag<<标签>> {
+ id:Int
+ name-名称: String
+ description-描述: String
+ icon-图标: ?String
+ color-颜色: ?String
+ status-状态: Enum
+ cluster-分群: String 
 
}

@enduml
```

```plantuml
@startuml

enum 文章状态{
草稿
已发布
已删除
}

enum 内容类型{

}

enum 审核状态{
待审核
已通过
已驳回
已撤销
}


@enduml


```

### 领域服务

- 文章服务
	- 保存
	- 送审
	- 发布
	- 审核
- 分类服务
	- 创建、修改
	- 生成树结构
- 标签服务
	- 创建、修改
	- 使用查询


### 领域事件

- 文章
	- 文章创建事件
	- 文章修改事件
	- 文章送审事件
	- 文章审批事件
	- 文章发布事件
	- 文章删除事件

- 分类



### 领域仓库



### 数据库设计
