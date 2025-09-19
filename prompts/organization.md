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
- sort（同级排序）
- depth / path / lft+rgt（二选一，推荐 NestedSet）
- status（0=停用 1=启用）
- timestamps

### departments（部门）【核心】
- id
- org_id → organizations.id
- parent_id（自引用，无限级）
- name / short_name
- code（唯一索引，部门编号）
- 多管理者：通过 `department_managers` 维护（支持多位负责人/副负责人、历史追溯）
- sort
- depth
- status
- timestamps

必须：
- 提供 ancestors()/descendants()，使用 NestedSet trait
- 事件：DepartmentCreated / DepartmentMoved / DepartmentDeleted

### positions（职位）
- id
- name（如“Java 高级工程师”）
- code（职位编号）
- sequence（职位序列/通道：技术/产品/设计/职能/管理 等，字符串或枚举）
- level（排序/职级，数值越大级别越高，可选）
- parent_id（自引用，用于同一序列内的层级：如 工程师 → 高级 → 资深）
- description
- status
- timestamps



### members（成员，仅存“人”本身）
- id
- org_id → organizations.id
- member_no（成员编号/工号，唯一）
- name
- nickname
- avatar
- mobile
- email
- gender
- telephone
- hired_at / resigned_at
- status（在职 试用 离职）
- position_name（冗余：当前主职位名称，便于展示/检索）
- position_level（冗余：当前主职位职级，对应 positions.level）
- main_department_id → departments.id（冗余：当前主部门，便于快速过滤/授权）
- departments（JSON/数组，冗余：当前所有有效部门ID集合，用于范围判定）
- timestamps


冗余同步建议：以 `member_departments` 为权威源，在任职新增/结束/主部门变更时，事务内同步 `members.primary_department_id` 与 `members.department_ids`（升序去重）。
同时以 `member_positions` 为权威源，在主职位新增/结束/变更时，同步 `members.position_name`、`members.position_level`（若无主职位则置空）。


### member_departments（成员-部门 任职历史）【核心中间表】
- id
- member_id → members.id
- department_id → departments.id
- is_primary（是否主部门）
- started_at / ended_at（历史时间区间）

- 约束：unique(member_id, is_primary, ended_at is null) 确保仅一条“当前主部门”记录生效（以 ended_at 为 NULL 表示当前）

模型方法：
- primaryDepartment() / departments() / departmentHistory()
- 事件：MemberMovedDepartment

### member_positions（成员-职位 任职历史）【核心中间表】
- id
- member_id → members.id
- position_id → positions.id
- started_at / ended_at（历史时间区间）

- 约束：可根据需要增加 unique(member_id, position_id, started_at) 或限制同一时间仅一个主职位字段

模型方法：
- positions() / positionHistory()
- 事件：MemberPromoted / MemberPositionChanged

### department_managers（部门管理者 历史）【支持多管理者】
- id
- department_id → departments.id
- member_id → members.id
- is_primary（是否主要负责人，可选）
- started_at / ended_at（历史时间区间
- 约束：
  - unique(department_id, member_id, ended_at is null) 防止重复生效记录
  - 可选：unique(department_id, is_primary, ended_at is null) 限制同一时段仅一个主要负责人

模型方法：
- managers() / primaryManagers() / managerHistory()
- 事件：DepartmentManagerAssigned / DepartmentManagerRevoked

（可选）如需“汇报线”功能，可新增 `member_reports`（成员上下级历史）表，采用软历史建模，便于回溯任意月份的汇报链。



建议字段类型与索引：
- 所有 id 使用雪花或 uuid；`code`、`emp_num` 建唯一索引
- NestedSet 使用 `lft`、`rgt`、`depth`，并各建索引；或使用 `path`+`depth`
- 历史表使用 `(started_at, ended_at)` 范围查询索引


