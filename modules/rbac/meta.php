<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version'=>'1.0',
    'description'=>'权限控制模块。',

    'bootstrap'=>false,
    'deps'=>[],
    'man'=>[
        'main'=>[
            'url'=>'manager/index',
            'name'=>'角色权限控制',
            'permission'=>'rbac_index'
        ],
        'sub'=>[
            '角色管理'=>[
                'url'=>'manager/roles',
                'permission'=>'rbac_roles'
            ],
            '权限管理'=>[
                'url'=>'manager/permissions',
                'permission'=>'rbac_permissions'
            ],
        ],
        'permissions'=>[
            'rbac_index'=>'角色权限控制',
            'rbac_roles'=>'角色管理',
            'rbac_permissions'=>'权限管理',
        ],
    ],
];