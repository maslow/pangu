说明
===

ey2b - 基于Yii2.0.6 基础模板建立。


需求
---

>=PHP 5.4.0
>=MySQL 5.1


安装
---


git clone https://git.coding.net/maslow/ey2b.git


配置
---

### 初始化系统环境

1、在项目根目录运行命令：
  
```command
php yii init
```
  

### 数据库

1､ 创建数据库，编码utf8;

2、编辑 `config/db.php` 填写数据库连接配置：

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=你新建的数据库名',
    'username' => 'root',
    'password' => '填写你本地的数据库密码',
    'charset' => 'utf8',
];
```


### 安装系统模块

```command
php yii module/update
```


  
 
