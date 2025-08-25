---
alwaysApply: true
---

# 用户接口层(UI)代码规范

## 控制器 (Controllers)

### 规范
- **基类继承**: 继承适当的基础控制器类
- **RestfulActions**: 使用 `RestControllerActions` Trait 提供标准CRUD操作
- **静态属性配置**: 定义资源类、查询类、模型类和数据类
- **依赖注入**: 通过构造函数注入应用服务
- **权限控制**: 实现 `authorize()` 方法进行权限验证
- **查询作用域**: 在构造函数中设置查询作用域
- **当前Owner**: 使用 `RedJasmine\Support\Http\Controllers\UserOwnerTools` Trait 的 getOwner 获取当前登录的 Owner
### 代码示例
```php
class ArticleController extends Controller
{
    protected static string $resourceClass = ArticleResource::class;
    protected static string $paginateQueryClass = ArticleListQuery::class;
    protected static string $modelClass = Article::class;
    protected static string $dataClass = ArticleData::class;

    use RestControllerActions;

    public function __construct(
        protected ArticleApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 权限验证逻辑
        return true;
    }
}
```

### 资料控制器必需静态属性
```php
protected static string $resourceClass;      // API资源类
protected static string $paginateQueryClass; // 分页查询类
protected static string $modelClass;         // 模型类
protected static string $dataClass;          // 数据类
```

## API资源 (Resources)

### 规范
- **基类继承**: 继承 `RedJasmine\Support\UI\Http\Resources\Json\JsonResource`
- **数据转换**: 将模型数据转换为API响应格式
- **关联加载**: 合理处理关联数据的加载和展示
- **条件字段**: 根据条件显示不同的字段
- **命名规范**: 资源类名格式为 `{Entity}Resource`

### 代码示例
```php
class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'status' => $this->status,
            'is_show' => $this->is_show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 条件字段
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            
            // 关联资源
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            
            // 扩展信息
            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'content_type' => $this->extension->content_type,
                    'view_count' => $this->extension->view_count,
                ];
            }),
        ];
    }
}
```

## 请求验证 (Form Requests)

### 规范
- **基类继承**: 继承 `FormRequest`
- **验证规则**: 定义 `rules()` 方法返回验证规则
- **错误消息**: 定义 `messages()` 方法返回自定义错误消息
- **数据准备**: 实现 `prepareForValidation()` 方法准备验证数据
- **权限验证**: 实现 `authorize()` 方法进行权限验证

### 代码示例
```php
class ArticleCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'url'],
            'category_id' => ['nullable', 'integer', 'exists:article_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:article_tags,id'],
            'is_show' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '标题不能为空',
            'title.max' => '标题长度不能超过255个字符',
            'content.required' => '内容不能为空',
            'category_id.exists' => '分类不存在',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_show' => $this->boolean('is_show'),
        ]);
    }
}
```

## 路由定义 (Routes)

### 规范
- **目录结构**: 按角色划分，每个角色对应一个目录
- **命名规范**: 路由格式为 `{role}`
- **路由文件**: 定义路由类 `{Domain}{Role}Route`
- **方法分离**: 分别定义 `api()` 和 `web()` 方法

### 代码示例
```php
class ArticleUserRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'article'], function () {
            Route::apiResource('articles', ArticleController::class);
            Route::get('categories/tree', [ArticleCategoryController::class, 'tree']);
            Route::apiResource('categories', ArticleCategoryController::class);
            Route::apiResource('tags', ArticleTagController::class);
        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'article'], function () {
            Route::get('articles', [ArticleController::class, 'index']);
            Route::get('articles/{id}', [ArticleController::class, 'show']);
        });
    }
}
```

## 中间件 (Middleware)

### 规范
- **职责单一**: 每个中间件只处理一种特定的逻辑
- **性能优化**: 避免在中间件中执行重量级操作
- **错误处理**: 合理处理异常情况
- **可配置**: 支持通过参数配置中间件行为

### 代码示例
```php
class CheckArticleOwner
{
    public function handle($request, Closure $next, ...$guards)
    {
        $articleId = $request->route('id');
        $article = Article::findOrFail($articleId);
        
        if ($article->owner_id !== auth()->id()) {
            throw new UnauthorizedException('无权访问此文章');
        }
        
        return $next($request);
    }
}
```

## 响应格式规范

### 成功响应
```php
// 单条数据
{
    "data": {
        "id": 1,
        "title": "文章标题",
        "content": "文章内容"
    }
}

// 列表数据
{
    "data": [
        {
            "id": 1,
            "title": "文章标题1"
        },
        {
            "id": 2,
            "title": "文章标题2"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100
    }
}
```

### 错误响应
```php
{
    "message": "验证失败",
    "errors": {
        "title": ["标题不能为空"],
        "content": ["内容不能为空"]
    }
}
```
# 用户接口层(UI)代码规范

## 控制器 (Controllers)

### 规范
- **基类继承**: 继承适当的基础控制器类
- **RestfulActions**: 使用 `RestControllerActions` Trait 提供标准CRUD操作
- **静态属性配置**: 定义资源类、查询类、模型类和数据类
- **依赖注入**: 通过构造函数注入应用服务
- **权限控制**: 实现 `authorize()` 方法进行权限验证
- **查询作用域**: 在构造函数中设置查询作用域

### 代码示例
```php
class ArticleController extends Controller
{
    protected static string $resourceClass = ArticleResource::class;
    protected static string $paginateQueryClass = ArticleListQuery::class;
    protected static string $modelClass = Article::class;
    protected static string $dataClass = ArticleData::class;

    use RestControllerActions;

    public function __construct(
        protected ArticleApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 权限验证逻辑
        return true;
    }
}
```

### 必需静态属性
```php
protected static string $resourceClass;      // API资源类
protected static string $paginateQueryClass; // 分页查询类
protected static string $modelClass;         // 模型类
protected static string $dataClass;          // 数据类
```

## API资源 (Resources)

### 规范
- **基类继承**: 继承 `RedJasmine\Support\UI\Http\Resources\Json\JsonResource`
- **数据转换**: 将模型数据转换为API响应格式
- **关联加载**: 合理处理关联数据的加载和展示
- **条件字段**: 根据条件显示不同的字段
- **命名规范**: 资源类名格式为 `{Entity}Resource`

### 代码示例
```php
class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'status' => $this->status,
            'is_show' => $this->is_show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 条件字段
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            
            // 关联资源
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            
            // 扩展信息
            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'content_type' => $this->extension->content_type,
                    'view_count' => $this->extension->view_count,
                ];
            }),
        ];
    }
}
```

## 请求验证 (Form Requests)

### 规范
- **基类继承**: 继承 `FormRequest`
- **验证规则**: 定义 `rules()` 方法返回验证规则
- **错误消息**: 定义 `messages()` 方法返回自定义错误消息
- **数据准备**: 实现 `prepareForValidation()` 方法准备验证数据
- **权限验证**: 实现 `authorize()` 方法进行权限验证

### 代码示例
```php
class ArticleCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'url'],
            'category_id' => ['nullable', 'integer', 'exists:article_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:article_tags,id'],
            'is_show' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '标题不能为空',
            'title.max' => '标题长度不能超过255个字符',
            'content.required' => '内容不能为空',
            'category_id.exists' => '分类不存在',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_show' => $this->boolean('is_show'),
        ]);
    }
}
```

## 路由定义 (Routes)

### 规范
- **目录结构**: 按角色划分，每个角色对应一个目录
- **命名规范**: 路由格式为 `{role}`
- **路由文件**: 定义路由类 `{Domain}{Role}Route`
- **方法分离**: 分别定义 `api()` 和 `web()` 方法

### 代码示例
```php
class ArticleUserRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'article'], function () {
            Route::apiResource('articles', ArticleController::class);
            Route::get('categories/tree', [ArticleCategoryController::class, 'tree']);
            Route::apiResource('categories', ArticleCategoryController::class);
            Route::apiResource('tags', ArticleTagController::class);
        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'article'], function () {
            Route::get('articles', [ArticleController::class, 'index']);
            Route::get('articles/{id}', [ArticleController::class, 'show']);
        });
    }
}
```

## 中间件 (Middleware)

### 规范
- **职责单一**: 每个中间件只处理一种特定的逻辑
- **性能优化**: 避免在中间件中执行重量级操作
- **错误处理**: 合理处理异常情况
- **可配置**: 支持通过参数配置中间件行为

### 代码示例
```php
class CheckArticleOwner
{
    public function handle($request, Closure $next, ...$guards)
    {
        $articleId = $request->route('id');
        $article = Article::findOrFail($articleId);
        
        if ($article->owner_id !== auth()->id()) {
            throw new UnauthorizedException('无权访问此文章');
        }
        
        return $next($request);
    }
}
```

## 响应格式规范

### 成功响应
```php
// 单条数据
{
    "data": {
        "id": 1,
        "title": "文章标题",
        "content": "文章内容"
    }
}

// 列表数据
{
    "data": [
        {
            "id": 1,
            "title": "文章标题1"
        },
        {
            "id": 2,
            "title": "文章标题2"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100
    }
}
```

### 错误响应
```php
{
    "message": "验证失败",
    "errors": {
        "title": ["标题不能为空"],
        "content": ["内容不能为空"]
    }
}
```
