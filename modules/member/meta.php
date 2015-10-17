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
    'man' => [
        'main' => [
            'name' => '用户管理',
            'url' => ['/member/manager/index'],
        ],
        'sub' => [
            [
                'name' => '管理用户',
                'url' => ['/member/manager/index'],
            ],
            [
                'name' => '添加用户',
                'url' => ['/member/manager/create'],
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