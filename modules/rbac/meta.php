<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version' => '1.0',
    'description' => '权限控制模块。',

    'bootstrap' => false,
    'deps' => [],
    'manager' => [
        'menu' => [
            'label' => '角色控制',
            'url' => ['/rbac/manager/roles'],
            'items' => [
                [
                    'label' => '管理系统角色',
                    'url' => ['/rbac/manager/roles'],
                    'permission' => 'rbac.roles.list'
                ],
                [
                    'label' => '创建系统角色',
                    'url' => ['/rbac/manager/create-role'],
                    'permission' => 'rbac.roles.create'
                ],
            ],
        ],
        'permissions' => [
            'rbac.roles.create' => '创建系统角色',
            'rbac.roles.update' => '更新系统角色',
            'rbac.roles.delete' => '删除系统角色',
            'rbac.roles.list' => '浏览系统角色',
        ],
    ],
];