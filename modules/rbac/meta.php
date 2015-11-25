<?php

return [
    'version' => '1.0',
    'description' => '权限控制模块。',

    'bootstrap' => false,
    'deps' => [],
    'manager' => [
        'menu' => [
            'label' => Yii::t('rbac', 'Roles'),
            'url' => ['/rbac/manager/roles'],
            'items' => [
                [
                    'label' => Yii::t('rbac', 'Role List'),
                    'url' => ['/rbac/manager/roles'],
                    'permission' => [
                        'rbac.roles.list',
                        'rbac.roles.update',
                        'rbac.roles.delete',
                    ]
                ],
                [
                    'label' => Yii::t('rbac', 'Create Role'),
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