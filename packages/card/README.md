# 卡密仓库

提供虚拟卡密的存储、绑定商品、发送的卡密仓库配合商品模块 和 订单模块一起使用

## 安装

Via Composer

``` bash
$ composer require red-jasmine/card
```

## 卡密模型设计

```plantuml
package 卡密 {
	class 卡密内容{
	 所属者【类型、ID】
	 所在分组
	 内容
	 是否循环
	}
	class 卡密组{
	 所属者【类型、ID】
	 分组名称
	}

	class 卡密绑定商品{
	 	 所属者【类型、ID】
		 卡密分组
		 商品类型
		 商品ID
		 SKU ID
		 
		}
}

```

### 事件

- 卡密创建
- 卡密使用
- 卡密修改
- 邮件发送

### 扩展点

- 创建
- 发送
