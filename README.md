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
	 分组
	 内容
	 是否循环
	}
	class 卡密组{
	 分组名称
	}

	class 卡密绑定商品{
		 商品类型
		 商品ID
		 SKU ID
		 卡密分组
		}
}

```

### 
