<?php

return [
    'version' => '1.0',
    'description' => '后台管理中心模块。',

    'bootstrap' => true,
    'deps' => [
        'rbac',
    ],
    'manager' => [
        'menu' => [
            [
                'label' => Yii::t('backend', 'Managers'),
                'url' => ['/backend/manager/list'],
                'items' => [
                    [
                        'label' => Yii::t('backend', 'Manager List'),
                        'url' => ['/backend/manager/list'],
                        'permission' => [
                            'backend.managers.list',
                            'backend.managers.update',
                            'backend.managers.delete',
                        ],
                    ],
                    [
                        'label' => Yii::t('backend', 'Create Manager'),
                        'url' => ['/backend/manager/create'],
                        'permission' => 'backend.managers.create',
                    ],
                    [
                        'label' => Yii::t('backend', 'Reset Password'),
                        'url' => ['/backend/manager/reset-password'],
                        'permission' => 'backend.managers.reset.password',
                    ],
                ]
            ],
        ],
        'permissions' => [
            'backend.managers.create' => '创建管理员',
            'backend.managers.list' => '浏览管理员',
            'backend.managers.update' => '更新管理员',
            'backend.managers.delete' => '删除管理员',
            'backend.managers.reset.password' => '修改自身密码'
        ]
    ],
];