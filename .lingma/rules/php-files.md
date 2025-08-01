---
globs: ["*.php"]
description: "PHP文件编码规范，仅适用于PHP文件"
---

# PHP文件编码规范

## 基本要求
- 使用 `<?php` 开始标签，不使用 `<?` 短标签
- 文件末尾不要使用 `?>` 结束标签
- 使用UTF-8编码，无BOM
- 行尾使用Unix LF (\\n)
- 文件末尾必须有一个空行

## 文件结构
每个PHP文件必须按以下顺序组织：

1. **开始标签和严格类型声明**
2. **文件级PHPDoc注释**（可选）
3. **命名空间声明**
4. **use声明**
5. **类定义**

## 严格类型声明
每个PHP文件开头必须包含严格类型声明：
```php
<?php

declare(strict_types=1);

namespace Your\Namespace;
```

## 命名空间和导入
- 每个文件必须有命名空间声明
- namespace声明后必须有一个空行
- use声明按类型分组：
  1. 标准库和框架类
  2. 第三方库
  3. 项目内部类
- 每组之间用空行分隔

```php
<?php

declare(strict_types=1);

namespace RedJasmine\Article\Application\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Spatie\LaravelData\Data;

use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Support\Application\ApplicationService;
```

## 类结构顺序
1. 类常量
2. 属性（按可见性排序：public, protected, private）
3. 构造函数
4. 魔术方法（__toString, __invoke等）
5. 公共方法
6. 受保护方法
7. 私有方法

## 属性和方法声明
- 所有属性必须声明可见性
- 所有方法必须声明可见性
- 所有方法参数必须有类型声明
- 所有方法必须有返回类型声明
- 使用nullable类型时使用 `?Type` 格式
- 使用联合类型时使用 `Type1|Type2` 格式

## 代码格式
- 使用4个空格缩进
- 左花括号独占一行
- 方法之间空一行
- 逻辑块之间空一行

## PHPDoc注释
- 类和方法必须有完整的PHPDoc注释
- 复杂逻辑必须有行内注释
- 使用中文注释
- 注释与代码保持同步

### 类注释格式
```php
/**
 * 文章应用服务
 * 
 * 负责处理文章相关的业务逻辑
 * 
 * @package RedJasmine\Article\Application\Services
 */
```

### 方法注释格式
```php
/**
 * 发布文章
 * 
 * @param int $id 文章ID
 * @return Article 发布后的文章
 * @throws ArticleNotFoundException 当文章不存在时
 * @throws ArticleException 当文章状态不允许发布时
 */
```

## 完整示例
```php
<?php

declare(strict_types=1);

namespace RedJasmine\Article\Application\Services;

use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 文章应用服务
 * 
 * 负责处理文章相关的业务逻辑
 * 
 * @package RedJasmine\Article\Application\Services
 */
final class ArticleApplicationService extends ApplicationService
{
    /**
     * 文章仓库
     */
    public readonly ArticleRepositoryInterface $repository;

    /**
     * 应用服务构造函数
     * 
     * @param ArticleRepositoryInterface $repository 文章仓库
     */
    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 发布文章
     * 
     * @param int $id 文章ID
     * @return Article 发布后的文章
     * @throws ArticleNotFoundException 当文章不存在时
     * @throws ArticleException 当文章状态不允许发布时
     */
    public function publish(int $id): Article
    {
        $article = $this->repository->find($id);
        
        if (!$article) {
            throw new ArticleNotFoundException($id);
        }
        
        if (!$article->canPublish()) {
            throw new ArticleException('文章状态不允许发布');
        }
        
        $article->publish();
        $this->repository->save($article);
        
        return $article;
    }
}
