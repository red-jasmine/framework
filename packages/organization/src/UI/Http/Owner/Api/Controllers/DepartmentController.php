<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Controllers;

use RedJasmine\Organization\Application\Services\Department\DepartmentApplicationService;
use RedJasmine\Organization\Application\Services\Department\Queries\PaginateQuery;
use RedJasmine\Organization\Domain\Data\DepartmentData;
use RedJasmine\Organization\Domain\Models\Department;
use RedJasmine\Organization\UI\Http\Owner\Api\Resources\DepartmentResource;
use RedJasmine\Support\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = DepartmentResource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Department::class;
    protected static string $dataClass = DepartmentData::class;

    public function __construct(
        protected DepartmentApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前用户所属组织的部门
        $this->service->repository->withQuery(function ($query) {
            $query->where('org_id', $this->getOwner()->id);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }

    /**
     * 获取部门树形结构
     */
    public function tree(Request $request)
    {
        $departments = Department::query()
            ->where('org_id', $this->getOwner()->id)
            ->where('status', 'active')
            ->orderBy('sort')
            ->get();

        $tree = $this->buildTree($departments->toArray());

        return response()->json([
            'data' => $tree
        ]);
    }

    /**
     * 构建树形结构
     */
    private function buildTree(array $departments, $parentId = null): array
    {
        $tree = [];

        foreach ($departments as $department) {
            if ($department['parent_id'] == $parentId) {
                $children = $this->buildTree($departments, $department['id']);
                if (!empty($children)) {
                    $department['children'] = $children;
                }
                $tree[] = $department;
            }
        }

        return $tree;
    }
}
