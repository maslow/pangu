<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version' => '1.0',
    'description' => '中央控制台模块。',

    'bootstrap' => true,
    'deps' => [],
    'manager' => [
        'menu' => [
            'label' => '管理员控制',
            'url' => ['/man/manager/list'],
            'items' => [
                [
                    'label' => '管理员管理',
                    'url' => ['/man/manager/list'],
                    'permission'=>'man.managers.list',
                ],
                [
                    'label' => '创建管理员',
                    'url' => ['/man/manager/create'],
                    'permission'=>'man.managers.create',
                ],
            ]
        ],
        'permissions' => [
            'man.managers.create' => '创建管理员',
            'man.managers.list' => '浏览管理员',
            'man.managers.update' => '更新管理员',
            'man.managers.delete' => '删除管理员',
        ]
    ],
];