<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version' => '1.0',
    'description' => '后台管理中心模块。',

    'bootstrap' => true,
    'deps' => [],
    'manager' => [
        'menu' => [
            [
                'label' => Yii::t('man', 'Managers'),
                'url' => ['/man/manager/list'],
                'items' => [
                    [
                        'label' => Yii::t('man', 'Manager List'),
                        'url' => ['/man/manager/list'],
                        'permission' => [
                            'man.managers.list',
                            'man.managers.update',
                            'man.managers.delete',
                        ],
                    ],
                    [
                        'label' => Yii::t('man', 'Create Manager'),
                        'url' => ['/man/manager/create'],
                        'permission' => 'man.managers.create',
                    ],
                    [
                        'label' => Yii::t('man', 'Reset Password'),
                        'url' => ['/man/manager/reset-password'],
                        'permission' => 'man.managers.reset.password',
                    ],
                ]
            ],
        ],
        'permissions' => [
            'man.managers.create' => '创建管理员',
            'man.managers.list' => '浏览管理员',
            'man.managers.update' => '更新管理员',
            'man.managers.delete' => '删除管理员',
            'man.managers.reset.password' => '修改自身密码'
        ]
    ],
];