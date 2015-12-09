Member Module for Pangu
========================

Member模块只提供了用户管理、登陆、注册等最基本网站会员功能，在实际项目中使用时，一般需要对其功能进行二次开发。

说明
====

基础信息
-------
    @ 标识 : member
    @ 全局引导 : 是
    @ 必要模块 : 否
    @ 数据迁移 : 是

导出菜单
-------
    * 用户管理  
        > 用户列表  
        > 创建用户  

导出权限
-------
    * member.users.create   > 添加用户
    * member.users.list     > 浏览用户
    * member.users.update   > 更新用户
    * member.users.delete   > 删除用户
    * member.users.view     > 查看用户
    
依赖模块
-------
    * backend
    
安装
---
    1. 将模块目录拷贝到@app/modules目录下
    
    2. 在项目根目录运行指令
        ```
            php yii module/update
        ```
