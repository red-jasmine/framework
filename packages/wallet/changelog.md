# 钱包模块变更日志

## [未发布]

### 新增
- 钱包充值模块命令和命令处理器
  - `CreateRechargeCommand` - 充值单创建命令
  - `InitiatePaymentCommand` - 充值单发起支付命令  
  - `CompletePaymentCommand` - 充值单支付完成命令
  - `FailPaymentCommand` - 充值单支付失败命令
  - 对应的命令处理器实现
- 钱包充值转换器 `WalletRechargeTransformer`
- 钱包充值使用示例和文档
- 数据库迁移文件添加缺失字段

### 修改
- 更新 `WalletRechargeApplicationService` 宏配置
- 为 `WalletRecharge` 模型添加 `HasSnowflakeId` Trait

### 技术细节
- 所有命令处理器都实现了事务管理
- 支付完成时自动创建钱包交易记录
- 支持多种支付方式和渠道
- 完整的支付状态流转管理
- 详细的错误处理和日志记录
