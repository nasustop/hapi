# Introduction

基于`Hyperf`的API快速开发模板，目标是包含基础的后台权限管理

## 数据库迁移
### 生成迁移文件
```shell
# 创建表，文件名请使用版本号；表名可选
php bin/hyperf.php gen:migration createSystemUser --create=system_user
# 修改表，文件名请使用版本号；表明可选
php bin/hyperf.php gen:migration updateSystemUser --table=system_user
```
### 执行数据库迁移
```shell
php bin/hyperf.php migrate
```

### 生成数据填充文件
```shell
php bin/hyperf.php gen:seeder seeder_system_user
```
### 执行数据库迁移及填充数据
```shell
php bin/hyperf.php migrate --seed
```

# 代码生成
### 生成代码文件
```shell
php bin/hyperf.php hapi:gen:code system_user
```
### 原生生成model文件
```shell
php bin/hyperf.php gen:model system_user --path=src\\SystemBundle\\Model --uses=App\\Model\\Model
```

# 测试
```shell
composer test -- [class] --filter=[function]

# 示例
composer test -- test/SystemBundle/Backend/SystemMenuControllerTest.php --filter=testMenu
```