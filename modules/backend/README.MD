Backend Module for Pangu
========================

Backend是后台管理中心模块，集成了管理员(Manager)、管理控制台，是Pangu的核心模块。

Backend通过读取每个模块的`导出菜单`与`导出权限`配置来完成对所有模块的集中式管理。

说明
====

基础信息
-------
    @ 标识 : backend
    @ 全局引导 : 是
    @ 必要模块 : 是
    @ 数据迁移 : 是

导出菜单
-------
    * 管理员控制  
        > 管理员列表  
        > 新建管理员  
        > 修改密码

导出权限
-------
    *  backend.managers.create          > 创建管理员
    *  backend.managers.list            > 浏览管理员
    *  backend.managers.update          > 更新管理员
    *  backend.managers.delete          > 删除管理员
    *  backend.managers.reset.password  > 修改自身密码
    
依赖模块
-------
    * rbac
    

安装
---
    1. 将模块目录拷贝到@app/modules目录下
    
    2. 在项目根目录运行指令
        ```
            php yii module/update
        ```
