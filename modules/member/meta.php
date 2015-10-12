<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version'=>'1.0',
    'description'=>'用户模块，用户认证、编辑、管理。',

    'bootstrap'=>true,
    'man'=>[
        'main'=>[
            'url'=>'manager/list',
            'name'=>'用户管理',
            'permission'=>'member_list'
        ],
        'sub'=>[
            '添加用户'=>[
                'url'=>'manager/insert',
                'permission'=>'member_insert'
            ],
            '管理用户'=>[
                'url'=>'manager/list',
                'permission'=>'member_list'
            ],
        ],
        'permissions'=>[
            'member_insert'=>'添加用户',
            'member_list'=>'管理用户',
            'member_edit'=>'编辑用户',
            'member_delete'=>'删除用户',
        ],
    ],
    'deps'=>[]
];