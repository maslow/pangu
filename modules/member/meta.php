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
            'member.create' => '添加用户',
            'member.list' => '管理用户',
            'member.update' => '更新用户',
            'member.delete' => '删除用户',
        ],
    ],
    'deps' => [

    ]
];