# Organization 组织架构扩展包提示词（开箱即用蓝图）

基于 Red Jasmine Framework，构建“组织-部门-岗位-员工-汇报线”核心域的 Composer 扩展包，满足 80% 通用场景并保留充足扩展点（接口、Trait、事件、Repository、Policy）。本提示词可直接用于生成迁移与骨架代码。

## 目标
- 支持多法人/多公司树（可选）与部门 NestedSet 树
- 员工任职历史追溯、矩阵式汇报（部门与岗位关系通过任职表隐式关联）
- 可扩展：事件、仓库、策略、Trait、接口

## ER 模型清单（字段/主外键/业务含义/约束）

> 说明：如系统仅一家公司，可省 `organizations`，将 `org_id` 约定为 0。

### organizations（集团/公司/法人实体）
- id/uuid
- parent_id（自引用，支持集团树）
- name / short_name
- code（统一社会信用代码或内部编码）
- order（同级排序）
- depth / path / lft+rgt（二选一，推荐 NestedSet）
- status（0=停用 1=启用）
- timestamps

### departments（部门）【核心】
- id
- org_id → organizations.id
- parent_id（自引用，无限级）
- name / short_name
- code（唯一索引，部门编号）
- manager_id（负责人 employee_id，逻辑外键，可空）
- order
- depth / path / lft+rgt（推荐 NestedSet）
- status
- timestamps

必须：
- 提供 ancestors()/descendants()，使用 NestedSet trait
- 事件：DepartmentCreated / DepartmentMoved / DepartmentDeleted

### positions（岗位/职位）
- id
- name（如“Java 高级工程师”）
- code（岗位编号）
- job_grade_id → job_grades.id
- description
- is_manager（是否管理岗）
- status
- timestamps

### job_grades（职等/职级体系）
- id
- org_id → organizations.id
- name（如 P6）
- level（排序值）
- band（通道：管理/专业 等）
- timestamps

注：若无职等体系，可降级为 positions.level 字段。

### employees（员工，仅存“人”本身）
- id/uuid
- org_id → organizations.id
- emp_num（工号，唯一）
- real_name / avatar
- id_card / phone / email
- hired_at / resigned_at
- status（1=在职 2=试用 3=离职）
- timestamps

注意：不在此表保存部门/岗位，使用中间表实现历史追溯。

### employee_department_position（员工分配 & 历史）【核心中间表】
- id
- employee_id → employees.id
- department_id → departments.id
- position_id → positions.id
- is_primary（是否主部门）
- started_at / ended_at（历史时间区间）
- created_by（操作者）
- 约束：unique(employee_id, is_primary) 仅允许一条“当前主部门”记录生效（建议以 ended_at 为 NULL 表示当前）

模型方法：
- primaryAssignment() / assignments() / history()
- 事件：EmployeeMoved / EmployeePromoted

### employee_reports（汇报线/上下级）
- id
- employee_id（汇报人）→ employees.id
- manager_id（被汇报人）→ employees.id
- department_id（上下文部门，可空）→ departments.id
- started_at / ended_at
- 约束：unique(employee_id, department_id, ended_at is null)

说明：采用“软历史”建模，便于回溯任意月份的汇报链。

### 权限对接（spatie/laravel-permission）
- 内置建议权限：
  - department.view：可查看本部门数据
  - department.manage：可管理本部门及子部门
- 在 Policy 中通过 Department::isAncestorOf() 实现继承判断

## 迁移生成提示（可直接据此生成）

建议字段类型与索引：
- 所有 id 使用雪花或 uuid；`code`、`emp_num` 建唯一索引
- NestedSet 使用 `lft`、`rgt`、`depth`，并各建索引；或使用 `path`+`depth`
- 历史表使用 `(started_at, ended_at)` 范围查询索引

示例（伪结构清单，非 SQL）：
```text
organizations: id(pk), parent_id(idx), name, short_name, code(unique), order, lft(idx), rgt(idx), depth(idx), status, timestamps
departments: id(pk), org_id(idx), parent_id(idx), name, short_name, code(unique), manager_id, lft(idx), rgt(idx), depth(idx), order, status, timestamps
positions: id(pk), name, code(unique), job_grade_id(idx), description, is_manager, status, timestamps
job_grades: id(pk), org_id(idx), name, level, band, timestamps
employees: id/uuid(pk), org_id(idx), emp_num(unique), real_name, avatar, id_card, phone, email, hired_at(idx), resigned_at(idx), status, timestamps
employee_department_position: id(pk), employee_id(idx), department_id(idx), position_id(idx), is_primary, started_at(idx), ended_at(idx), created_by, unique(employee_id, is_primary, ended_at is null)
employee_reports: id(pk), employee_id(idx), manager_id(idx), department_id(idx), started_at(idx), ended_at(idx), unique(employee_id, department_id, ended_at is null)
```

## 目录结构（PSR-4：YourOrg\\Organization）
```
src/
├── Models/
│   ├── Organization.php
│   ├── Department.php
│   ├── Position.php
│   ├── JobGrade.php
│   ├── Employee.php
│   ├── EmployeeDepartmentPosition.php
│   └── EmployeeReport.php
├── Contracts/
│   ├── OrganizationInterface.php
│   ├── DepartmentTreeInterface.php
│   └── EmployeeAssignmentInterface.php
├── Repositories/
│   ├── DepartmentRepository.php   # with move(), rebuildPath()
│   └── EmployeeRepository.php
├── Events/
├── Policies/
├── Traits/
│   ├── BelongsToOrganization.php
│   └── HasNestedDepartment.php
└── database/
    ├── migrations/
    └── seeders/
```

## 代码骨架要求（契合 Red Jasmine 规范）

- 模型：实现 `HasSnowflakeId`、`SoftDeletes`（按需）、`HasOwner`（用于多租隔离）
- 应用层：`ApplicationService` + `{Action}CommandHandler`/`{Action}QueryHandler`
- 仓库：实现接口，树操作提供 `move(node, newParent)`、`rebuildPath()`；查询提供 `ancestorsOf()`、`descendantsOf()`
- 事件：
  - DepartmentCreated / DepartmentMoved / DepartmentDeleted
  - EmployeeMoved / EmployeePromoted
- Policy：
  - `department.view` 与 `department.manage`；`isAncestorOf()` 处理继承
- Trait：
  - `BelongsToOrganization`（统一 org 归属）
  - `HasNestedDepartment`（封装 NestedSet 常用方法）

## API 与查询建议
- RESTful：组织、部门、岗位、员工、任职、汇报线的标准 CRUD
- 过滤/排序/包含：遵循 Infrastructure 过滤/排序/包含规范（AllowedFilter / AllowedSort / allowedIncludes）
- 历史检索：按任意月份回溯员工主部门、岗位与汇报链（通过 ended_at=NULL + 时间区间判断）

## 生成器使用口令（示例）
- 生成迁移：根据“迁移生成提示”的伪结构清单创建表与索引
- 生成模型：为各模型添加关系
  - Department: parent/children, employees (through employee_department_position)
  - Employee: assignments/history, primaryAssignment, departments/positions (through employee_department_position)
  - Position: employees/departments (through employee_department_position)
- 生成仓库：`DepartmentRepository::move()`/`rebuildPath()`；`EmployeeRepository` 提供在某时点的组织快照查询
- 生成事件与监听：按上文事件名占位
- 生成 Policy：基于部门树的权限继承判断

一句话总结：采用“5 主表 + 历史中间表 + NestedSet 树”的最小可行组织架构，覆盖 80% 通用需求，其余以字段/表扩展。
