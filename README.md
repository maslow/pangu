说明
===

盘古 - 基于Yii2.0.6 基础模板建立。


需求
---

>=PHP 5.4.0


安装
---


git clone https://github.com/Maslow/pangu.git


配置
---

### 初始化系统环境

1、在项目根目录运行命令：
  
```command
php yii init
```
注：该命令可以指定将程序初始化为｀生产环境｀或｀开发环境｀，默认为｀开发环境｀，可以任意切换：
```command
php yii init dev
#or
php yii init prod
```

### 数据库

1､ 创建数据库，编码utf8:

```sql
create database pangu CHARACTER SET utf8 COLLATE utf8_unicode_ci;
```

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

### 初始化第一个超级管理员

```command
php yii init/admin
```

其它
---

后台管理中心地址:  http://localhost/pangu/web/index.php/man

  
 
