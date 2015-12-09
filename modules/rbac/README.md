RBAC Module for Pangu
========================

RBAC模块是基于Yii2提供的rbac机制开发而来，通过每个模块的`导出权限`配置来收集所有模块导出的权限，并将这些权限导入认证管理系统(AuthManager)。

RBAC模块提供了权限收集（导入）、新建角色、编辑角色、角色的权限分配、删除角色功能。

说明
====

基础信息
-------
    @ 标识 : rbac
    @ 全局引导 : 否
    @ 必要模块 : 是
    @ 数据迁移 : 是

导出菜单
-------
    * 角色管理  
        > 角色列表  
        > 新建角色  

导出权限
-------
    * rbac.roles.create   > 创建系统角色',
    * rbac.roles.update   > 更新系统角色',
    * rbac.roles.delete   > 删除系统角色',
    * rbac.roles.list     > 浏览系统角色',
    
依赖模块
-------
    无
    

安装
---
    1. 将模块目录拷贝到@app/modules目录下
    
    2. 在项目根目录运行指令
        ```
            php yii module/update
        ```
