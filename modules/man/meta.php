<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version'=>'1.0',
    'description'=>'中央控制台模块。',

    'bootstrap'=>true,
    'deps'=>[],
    'man'=>[
        'main'=>[
            'name'=>'管理员控制',
            'url'=>['/man/manager/list']
        ],
        'sub'=>[
            [
                'name'=>'管理员管理',
                'url'=>['/man/manager/list']
            ],
            [
                'name'=>'创建管理员',
                'url'=>['/man/manager/create']
            ],
        ]
    ],
];