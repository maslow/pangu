<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version' => '1.0',
    'description' => '用户模块，用户认证、编辑、管理。',

    'bootstrap' => true,
    'manager' => [
        'menu' => [
            'label' => Yii::t('member', 'Users'),
            'url' => ['/member/manager/index'],
            'items' => [
                [
                    'label' => Yii::t('member', 'User List'),
                    'url' => ['/member/manager/index'],
                    'permission' => [
                        'member.users.list',
                        'member.users.update',
                        'member.users.delete',
                    ],
                ],
                [
                    'label' => Yii::t('member', 'Create User'),
                    'url' => ['/member/manager/create'],
                    'permission' => 'member.users.create'
                ],
            ],
        ],
        'permissions' => [
            'member.users.create' => '添加用户',
            'member.users.list' => '浏览用户',
            'member.users.update' => '更新用户',
            'member.users.delete' => '删除用户',
            'member.users.view' => '查看用户',
        ],
    ],
    'deps' => [

    ]
];